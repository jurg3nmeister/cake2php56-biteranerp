/* (c)Bittion | Created: 23/09/2014 | Developer:reyro | JS: SalOffers/create */
jQuery(document).ready(function () {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 23/09/2014 | Developer: reyro | Description: initiate select2
    jQuery('#SalOfferSalCustomerId').select2();
    jQuery('#SalOfferSalCustomerId').select2('focus');


//    Created: 23/09/2014 | Developer: reyro | Description: initiate datepicker
    jQuery('#SalOfferDate').datepicker({
        dateFormat: 'dd/mm/yy',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });

//    Created: 23/09/2014 | Developer: reyro | Description: shows modal 
    jQuery('#btnModalCreateProduct').click(function () {
        if (jQuery('#SalOfferDate').val() === '' || jQuery('#SalOfferSalCustomerId').val() === null) {
            alert('Primero debe ingresar una fecha y seleccionar un cliente');
            return false;
        }
        jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});

        fnReadProducts(jQuery('#SalOfferId').val());
    });

//    Created: 23/09/2014 | Developer: reyro | Description: focus on select product when modal show  | Obs: must be outside any event/function otherwise it will fire many times
    jQuery('#modalProduct').on('shown.bs.modal', function () {
        if (jQuery('#SalOffersDetailId').val() === '') {//CREATE
            jQuery('#SalOffersDetailInvProductId').select2('focus');
        } else {//UPDATE
            jQuery('#SalOffersDetailSalePrice').focus();
        }
    });

//    Created: 23/09/2014 | Developer: reyro | Description: Always reset form when modal hides | Obs: must be outside any event/function otherwise it will fire many times
    $('#modalProduct').on('hidden.bs.modal', function () {
//        jQuery('#SalOffersDetailSaveForm').get(0).reset();
        ValidateOfferDetail.resetForm();
        jQuery('label').removeClass('state-success state-error');
        jQuery('#SalOffersDetailId').val('');
        jQuery('#btnModalCreateProduct').focus();
    });

//    Created: 23/09/2014 | Developer: reyro | Description: Validates Save Offer
    jQuery("#SalOfferSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
//            'data[SalCustomersEmployee][email]': {
//                email: true
//            }
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 24/09/2014 | Developer: reyro | Description: Validates Save Offer
    var ValidateOfferDetail = jQuery("#SalOffersDetailSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSaveOfferAndDetail();
        },
        // Rules for form validation
        rules: {
            'data[SalOffersDetail][inv_product_id]': {
                required: true
            },
            'data[SalOffersDetail][sale_price]': {
                required: true,
                number: true,
                positiveValue: true,
                min: 0.01
            },
            'data[SalOffersDetail][quantity]': {
                required: true,
                digits: true,
//                positiveValue:true,
                min: 1
            }
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 25/09/2014 | Developer: reyro | Description: .validate extra method, checks not zero only positive
    jQuery.validator.addMethod('positiveValue', function (value, element) {
        if (value < 0) {
            return false;
        }
        return true;
    }, 'Solo se acepta números mayores a 0');

//    Created: 24/09/2014 | Developer: reyro | Description: event change customers
    jQuery('#SalOfferSalCustomerId').change(function () {
        fnReadCustomerEmployees($(this).val());
    });

//    Created: 24/09/2014 | Developer: reyro | Description: event change customers
    jQuery('#SalOffersDetailInvProductId').change(function () {
        fnReadProductPrices($(this).val());
    });

//    Created: 25/09/2014 | Developer: reyro | Description: read products details | Request: Ajax
    var table1 = jQuery('#SalOffersDetailIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnReadOffersDetail'
        , 'sServerMethod': 'POST'
        , 'bPaginate': false
//        ,"bProcessing": true //not used because there isnt a good position to show it
//        , "iDisplayLength": -1 //doesn't work
//        ,"sDom": 'frt' //shows find processing and table, but it isn't formatted
//        , "sDom": "<'dt-toolbar'<'col-xs-6'f><'col-xs-6'<'toolbar'>>r>" +
//                "t"
////						"<'dt-toolbar-footer'<'col-xs-6'i><'col-xs-6'p>>"
        , 'sDom': 't'
//        , 'bInfo': false, //hides all , doesn't work
//            , 'bStateSave': true  //Saves the last search and/or pagination :)
        , "fnServerParams": function (aoData) {
            aoData.push(
                    {name: "offerId", value: jQuery('#SalOfferId').val()}
            );
        }
        , 'fnCreatedRow': function (nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[7]);
            jQuery('td:eq(7)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function (oSettings) {
            fnBindEventDataTableControls();
            var offerId = jQuery('#SalOfferId').val();
            if (offerId !== '') {
                fnReadTotal(offerId);
            }
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [0, 7]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });

//************************************** EXEC MAIN - END **************************************

//    Created: 25/09/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var buttonHtml = '<button type="button" id="update-' + id + '" class="btn btn-primary btn-xs btnUpdate" title="Editar"><i class="fa fa-pencil"></i></button>';
        buttonHtml += ' <button type="button" id="delete-' + id + '" class="btn btn-danger btn-xs btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 25/09/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function (event) {
                fnDeleteOffersDetail(id); //(id, object)
                event.preventDefault();
            });
        });

        jQuery('.btnUpdate').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            fnReadOfferDetailUpdate(id);
            jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: fills autocomplete with customers employees | Request:Ajax
    function fnReadCustomerEmployees(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadCustomerEmployees',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                fillAutoComplete(response['customersEmployees'], 'listCustomersEmployee');
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: fills autocomplete with customers employees | Request:Ajax
    function fnReadProducts(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProducts',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                fillSelectProducts(response['products'], 'SalOffersDetailInvProductId');
                jQuery('#SalOffersDetailInvProductId').closest('section').show();
                jQuery('#sectionUpdateOfferDetail').hide();
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: fills salePrice and price | Request:Ajax
    function fnReadProductPrices(productId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProductPrices',
            dataType: 'json',
            data: {
                productId: productId
            },
            success: function (response) {
                jQuery('#SalOffersDetailPrice').val(response['lastPrice']);
                jQuery('#SalOffersDetailSalePrice').val(response['lastPrice']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: fills total | Request:Ajax
    function fnReadTotal(offerId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadTotal',
            dataType: 'json',
            data: {
                offerId: offerId
            },
            success: function (response) {
                if(response['total'] === null){
                    jQuery('#spanTotal').text('0.00');
                }else{
                    jQuery('#spanTotal').text(response['total']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: fills total | Request:Ajax
    function fnReadOfferDetailUpdate(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadOfferDetailUpdate',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                jQuery('#SalOffersDetailId').val(id);
                var selectProduct = jQuery('#SalOffersDetailInvProductId');
                selectProduct.closest('section').hide();
                selectProduct.empty();
                selectProduct.append('<option value="' + response[0]['SalOffersDetail']['inv_product_id'] + '" selected="selected"></option>');
                jQuery('#sectionUpdateOfferDetail').text('[' + response[0]['InvProduct']['code'] + '] ' + response[0]['InvProduct']['description']);
                jQuery('#sectionUpdateOfferDetail').show();
                jQuery('#SalOffersDetailPrice').val(response[0]['SalOffersDetail']['price']);
                jQuery('#SalOffersDetailSalePrice').val(response[0]['SalOffersDetail']['sale_price']);
                jQuery('#SalOffersDetailQuantity').val(response[0]['SalOffersDetail']['quantity']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro 
    function fillAutoComplete(data, id) {
        var autocomplete = jQuery('#' + id);
        //empty current autocomplete values
        autocomplete.empty();
        //fill the autocomplete with new data  
        $.each(data, function (index, value) {
            autocomplete.append('<option value="'
                    + index
                    + '">'
                    + value
                    + '</option>');
        });

    }

//    Created: 24/09/2014 | Developer: reyro 
    function fillSelectProducts(data, id) {
        var select = jQuery('#' + id);
        //empty current autocomplete values
        select.empty();
        //fill the autocomplete with new data  
//        if (selected === '') { //CREATE
        select.append('<option value="" disabled="disabled" selected="selected">Elija un producto </option>');
//        } else {//UPDATE
//            select.append('<option value="">Elija un producto </option>');
//            //the selected
//        }

        $.each(data, function (index, value) {
            select.append('<option value="'
                    + index
                    + '">'
                    + value
                    + '</option>');
        });
        select.select2();
    }

//    Created: 24/09/2014 | Developer: reyro | Description: create or update | Request: Ajax
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalOfferSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#SalOfferId').val(response['data']['id']);
                    if (response['data']['system_code'] !== '') {
                        jQuery('#spanOfferTitle').html(': <STRONG>' + response['data']['system_code'] + '</STRONG>');
                    }
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/09/2014 | Developer: reyro | Description: create or update, offer and detail | Request: Ajax
    function fnSaveOfferAndDetail() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveOfferAndDetail',
            dataType: 'json',
            data: {
                data: jQuery('#SalOfferSaveForm').bittionSerializeObjectJson(),
                data2: jQuery('#SalOffersDetailSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#SalOfferId').val(response['data']['id']);
                    if (response['data']['system_code'] !== '') {
                        jQuery('#spanOfferTitle').html(': <STRONG>' + response['data']['system_code'] + '</STRONG>');
                    }
                    table1.draw();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
                jQuery('#modalProduct').modal('hide');
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalProduct').modal('hide');
            }
        });
    }

//    Created: 25/09/2014 | Developer: reyro | Description: deletes offers detail | Request:Ajax
    function fnDeleteOffersDetail(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteOffersDetail',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    table1.draw();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }
//END SCRIPT
});