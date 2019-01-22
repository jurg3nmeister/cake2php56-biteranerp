/* (c)Bittion | Created: 24/02/2015 | Developer:reyro | JS: SalSales/admin_save */
jQuery(document).ready(function () {
//START SCRIPT

    var SalSaleId = jQuery('#SalSaleId');

//************************************** EXEC MAIN - START **************************************
//    Created: 07/10/2014 | Developer: reyro | Description: initiate select2
//    jQuery('#SalSaleSalOfficeId').select2();
    jQuery('#SalSaleSalCustomerId').select2();
    jQuery('#SalSaleSalCustomerId').select2('focus');

    var hiddenLcState = jQuery('#SalSaleLcState').val();

    if (hiddenLcState === 'APPROVED') {
        jQuery('#spanDocumentState').text('VENTA APROBADA').addClass('bg-color-green');
        fnDisableEnableInputsSaleApproved();  //nothing will be disabled 'cause is admin_save
        jQuery('#btnDelete').hide();
        if (jQuery("#spanAuthorizationNumber").text() != "") {
            jQuery('#btnInvoice').show();
            jQuery('#divGenerateInvoice').hide();
        }else{
            jQuery('#btnInvoice').hide();
            jQuery('#divGenerateInvoice').show();
        }
    } else if (hiddenLcState === 'CANCELED') {
//        alert(jQuery('#spanDocumentState').text());
        jQuery('#spanDocumentState').text('VENTA ANULADA').addClass('bg-color-red');
        jQuery('#btnDelete').hide();
        jQuery('#divGenerateInvoice').show();
        if (jQuery("#spanAuthorizationNumber").text() != "") {
            jQuery('#btnInvoice').show();
            jQuery('#divGenerateInvoice').hide();
        }else{
            jQuery('#btnInvoice').hide();
            jQuery('#divGenerateInvoice').show();
        }
        fnDisableEnableInputsSaleApproved();//nothing will be disabled 'cause is admin_save
        fnDisableEnableInputsSaleCanceled();//nothing will be disabled 'cause is admin_save
    } else {
        jQuery('#btnCancel').hide();
        jQuery('#btnInvoice').hide();
        jQuery('#divGenerateInvoice').hide();
        fnHideDeleteButton();//when not saved
    }



//    Created: 07/10/2014 | Developer: reyro | Description: initiate datepicker
    jQuery('#SalSaleDate').datepicker({
        dateFormat: 'dd/mm/yy',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });

//    Created: 07/10/2014 | Developer: reyro | Description: focus on select product when modal show  | Obs: must be outside any event/function otherwise it will fire many times
    jQuery('#modalProduct').on('shown.bs.modal', function () {
        if (jQuery('#SalSalesDetailId').val() === '') {//CREATE
            jQuery('#SalSalesDetailInvProductId').select2('focus');
        } else {//UPDATE
            jQuery('#SalSalesDetailSalePrice').focus();
        }
    });

//    Created: 07/10/2014 | Developer: reyro | Description: Always reset form when modal hides | Obs: must be outside any event/function otherwise it will fire many times
    $('#modalProduct').on('hidden.bs.modal', function () {
//        jQuery('#SalSalesDetailAdminSaveForm').get(0).reset();
        ValidateSaleDetail.resetForm();
        jQuery('label').removeClass('state-success state-error');
        jQuery('#SalSalesDetailId').val('');
        jQuery('#btnModalCreateProduct').focus();
    });

//    Created: 07/10/2014 | Developer: reyro | Description: Validates Save Offer
    var ValidateSaleParameters = {
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
//            fnAdminSave();
        },
        // Rules for form validation
        rules: {
            'data[SalSale][discount]': {
                digits: true,
                min: 1,
                max: 100
            },
            //'data[SalSale][paid]': {
            //    required: true,
            //    number: true,
            //    positiveValue: true,
            //    min: 0.01
            //},
            'data[SalSale][payment_deadline]': {
                digits: true,
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
    };
    var ValidateSale = jQuery("#SalSaleAdminSaveForm").validate(ValidateSaleParameters);

//    Created: 07/10/2014 | Developer: reyro | Description: Validates Save Offer
    var ValidateSaleDetail = jQuery("#SalSalesDetailAdminSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSaveSaleAndDetail();
        },
        // Rules for form validation
        rules: {
            'data[SalSalesDetail][inv_product_id]': {
                required: true
            },
            'data[SalSalesDetail][sale_price]': {
                required: true,
                number: true,
                positiveValue: true,
                min: 0.01
            },
            'data[SalSalesDetail][quantity]': {
                required: true,
                digits: true,
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

//    Created: 07/10/2014 | Developer: reyro | Description: .validate extra method, checks not zero only positive
    jQuery.validator.addMethod('positiveValue', function (value, element) {
        if (value < 0) {
            return false;
        }
        return true;
    }, 'Solo se acepta números mayores a 0');

//    Created: 07/10/2014 | Developer: reyro | Description: event change customers
    jQuery('#SalSaleSalCustomerId').change(function () {
        fnReadCustomerEmployees($(this).val());
    });

//    Created: 07/10/2014 | Developer: reyro | Description: event change customers
    jQuery('#SalSalesDetailInvProductId').change(function () {
        fnReadProductPrices($(this).val());
    });

//    Created: 07/10/2014 | Developer: reyro | Description: on key up always 0
    jQuery('#SalSalePaid').keyup(function () {
        if ($(this).val() === '') {
            $(this).val('0.00');
        }
    });

//    Created: 07/10/2014 | Developer: reyro | Description: read products details | Request: Ajax
    var table1 = jQuery('#SalSalesDetailIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnReadSalesDetail'
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
                    {name: "id", value: jQuery('#SalSaleId').val()}
            );
        }
        , 'fnCreatedRow': function (nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[6]);
            jQuery('td:eq(6)', nRow).html(htmlTableButtons);

            var htmlTableButtons = fnPutCssWrapIfInvoice(aData[7]);
            jQuery('td:eq(2)', nRow).html(htmlTableButtons+' '+aData[2]);
        }
        , 'fnDrawCallback': function (oSettings) {
            fnBindEventDataTableControls();
            var id = jQuery('#SalSaleId').val();
            if (id !== '') {
                fnReadTotal(id);
            }
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [0, 6]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });

//    Created: 07/10/2014 | Developer: reyro | Description: shows modal
    jQuery('#btnModalCreateProduct').click(function () {

        if (jQuery('#SalSaleDate').val() === '' || jQuery('#SalSaleSalCustomerId').val() === null) {
            alert('Primero debe ingresar una fecha y seleccionar un cliente');
            return false;
        }

        //if (ValidateSale.valid()) {  //doesn't work
            jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});

            fnReadProducts(jQuery('#SalSaleId').val());
        //}
    });

//    Created: 08/10/2014 | Developer: reyro | Description: approve a sale
    jQuery('#btnApprove').click(function () {
        //Count details table rows
        var detailsTD = jQuery('#SalSalesDetailIndexDT tbody tr td').length;
        if (detailsTD === 1) {
            alert('Debe existir al menos un producto en detalle');
            return false;
        }
        ////////////////////////
        if (ValidateSale.valid()) {
            showBittionAlertModal({content: '¿Está seguro de APROBAR la venta?', title: 'Venta'});
            jQuery('#bittionBtnYes').click(function (event) {
                hideBittionAlertModal();
                event.preventDefault();
                fnAdminApproveSale();
            });
        }
    });

//    Created: 09/10/2014 | Developer: reyro | Description: cancel a sale
    jQuery('#btnCancel').click(function () {
        if (ValidateSale.valid()) {
            showBittionAlertModal({content: '¿Está seguro de ANULAR la venta?', title: 'Anular'});
            jQuery('#bittionBtnYes').click(function (event) {
                hideBittionAlertModal();
                event.preventDefault();
                fnCancelSale(jQuery('#SalSaleId').val());
            });
        }
    });

//    Created: 08/10/2014 | Developer: reyro | Description: submit Sales form
    jQuery('#SalSaleAdminSaveForm').submit(function () {
        var lcState = jQuery('#SalSaleLcState').val();
        //if (lcState !== 'APPROVED') {
        //    if (ValidateSale.valid()) {
                fnAdminSave();
        //    }
        //} else {
        //    if (ValidateSale.valid()) {
        //        fnSaveWhenApproved();
        //    }
        //}

    });

//    Created: 10/10/2014 | Developer: reyro | Description: on click delete sale
    jQuery('#btnDelete').click(function (event) {
        event.preventDefault();
        var id = jQuery('#SalSaleId').val();
        showBittionAlertModal({content: 'Se eliminará permanentemente. ¿Está seguro?', title: 'Eliminar'});
        jQuery('#bittionBtnYes').click(function (event) {
            fnDeleteInside(id);
            event.preventDefault();
        });
    });

//    Created: 13/10/2014 | Developer: reyro | Description: on click show invoice in another page
    jQuery('#btnInvoice').click(function (event) {
        event.preventDefault();
        var url = bittionUrlProjectAndController + 'invoice/id:' + SalSaleId.val();
        var win = window.open(url, '_blank'); //new tab   
        win.focus();
    });

//    Created: 04/06/2015 | Developer: reyro | Description: checkbox switch on off for invoice
    jQuery('#btnGenerateInvoice').click(function(event) {
        //if (jQuery(this).is(':checked')) {
        //    showBittionAlertModal({content: '¿Está seguro de Facturar para esta venta?', title: 'Facturar'});
        //    //jQuery('#bittionBtnYes').click(function(event) {
        //    //    alert('aqui se crea factura');
        //    //    event.preventDefault();
        //    //});
        //    jQuery('#bittionBtnNo').click(function(event) {
        //        //alert('No se quita');
        //        //jQuery(this).prop('checked', false); //this not working
        //        event.preventDefault();
        //    });
        //}else{
        //    //alert('No se puede deshacer una factura, tiene que CANCELARLA');
        //    bittionShowGrowlMessage('ERROR','Acción denegada!','No se puede deshacer una factura, tiene que CANCELARLA');
        //    //jQuery(this).prop('checked', true);
        //    fnToogleCheckbox(0, jQuery(this));
        //}
        showBittionAlertModal({content: 'La factura es definitiva ¿Está seguro?', title: 'Facturar'});
        jQuery('#bittionBtnYes').click(function(event) {
            //alert('aqui se crea factura');
            fnAdminGenerateInvoice();
            event.preventDefault();
        });
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 06/06/2015 | Developer: reyro | Description: Put css wrap if it's invoice
    function fnPutCssWrapIfInvoice(alternative_name) {
        if(alternative_name){
            //return '<span class="badge bg-color-blueDark txt-color-white">F</span>';
            //return '<span class="label label-info">A</span>';
            return '<span class="badge bg-color-blue">A</span>';
        }
        return '';
    }

//    Created: 05/06/2015 | Developer: reyro | Description: toogle Yes or No checkbox
    function fnToogleCheckbox(active, objectCheckbox) {
        if (active === 1) {
            objectCheckbox.prop('checked', false);
        } else {
            objectCheckbox.prop('checked', true);
        }
    }

//    Created: 08/10/2014 | Developer: reyro | Description: Approve a sale
    function fnAdminApproveSale() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnAdminApproveSale',
            dataType: 'json',
            data: {
                data: jQuery('#SalSaleAdminSaveForm').bittionSerializeObjectJson(),
                customerNit: jQuery('#spanCustomerNit').text(),
                customerNitName: jQuery('#spanCustomerNitName').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#SalSaleLcState').val('APPROVED');
                    jQuery('#spanDocumentState').text('VENTA APROBADA').addClass('bg-color-green');
                    //jQuery('#spanAuthorizationNumber').text(response['data']['authorizationNumber']);
                    //jQuery('#spanInvoiceNumber').text(response['data']['invoiceNumber']);
                    //jQuery('#spanControlCode').text(response['data']['invoiceControlCode']);
                    fnDisableEnableInputsSaleApproved();
                    jQuery('#divGenerateInvoice').show();
                    jQuery('#btnDelete').hide();
                    jQuery('#spanDebt').text('0.00');
                    //window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']); //only on save
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

//    Created: 05/06/2015 | Developer: reyro | Description: generate invoice
    function fnAdminGenerateInvoice() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnAdminGenerateInvoice',
            dataType: 'json',
            data: {
                data: jQuery('#SalSaleAdminSaveForm').bittionSerializeObjectJson(),
                customerNit: jQuery('#spanCustomerNit').text(),
                customerNitName: jQuery('#spanCustomerNitName').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    //jQuery('#SalSaleLcState').val('APPROVED');
                    //jQuery('#spanDocumentState').text('VENTA APROBADA').addClass('bg-color-green');
                    jQuery('#spanAuthorizationNumber').text(response['data']['authorizationNumber']);
                    jQuery('#spanInvoiceNumber').text(response['data']['invoiceNumber']);
                    jQuery('#spanControlCode').text(response['data']['invoiceControlCode']);
                    fnDisableEnableInputsSaleApproved();
                    jQuery('#divGenerateInvoice').hide();
                    jQuery('#btnInvoice').show();
                    jQuery('#btnDelete').hide();
                    jQuery('#spanDebt').text('0.00');
                    //window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']); //only on save
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

//    Created: 08/10/2014 | Developer: reyro | Description: Cancel a sale
    function fnCancelSale(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCancelSale',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#SalSaleLcState').val('CANCELED');
                    jQuery('#spanDocumentState').text('VENTA ANULADA').removeClass('bg-color-green').addClass('bg-color-red');
                    jQuery('#btnDelete').hide();
                    fnDisableEnableInputsSaleApproved();
                    fnDisableEnableInputsSaleCanceled();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }


//    Created: 10/10/2014 | Developer: reyro | Description: deletes | Request:Ajax
    function fnDeleteInside(id) {
        jQuery.ajax({
            type: 'POST',
            async: false,
            url: bittionUrlProjectAndController + 'fnDeleteInside',
            dataType: 'json',
            data: {
                id: id,
                systemCode: jQuery('#spanSaleTitle').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    var url = bittionUrlProjectAndController + 'index';
//                    var win=window.open(url, '_blank'); //new tab
                    var win = window.open(url, '_self');
                    win.focus();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 25/09/2014 | Developer: reyro | Description: hides delete button when is not saved| Request:Ajax
    function fnHideDeleteButton() {
        if (jQuery('#SalSaleId').val() === '') {
            jQuery('#btnDelete').hide();
        } else {
            if(jQuery('#spanDocumentState').text() == ''){
                jQuery('#btnDelete').show();
            }
        }
    }

//    Created: 08/10/2014 | Developer: reyro | Description: disable enable inputs when sale approved
    function fnDisableEnableInputsSaleApproved() {
        //'cause is admin, there isn't limitations
        //jQuery('#SalSaleDate').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSalePersonRequesting').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSaleDiscount').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSaleNote').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSaleSalCustomerId').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSaleSalCustomerId').select2();
        //jQuery('#SalSaleSalOfficeId').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('.btnUpdate').attr("disabled", "disabled");
        //jQuery('.btnDelete').attr("disabled", "disabled");
        //jQuery('#btnModalCreateProduct').attr("disabled", "disabled");

        jQuery('#SalSalePaymentDeadline').removeAttr('disabled').closest('label').removeClass('state-disabled');
        jQuery('#SalSalePaid').removeAttr('disabled').closest('label').removeClass('state-disabled');
        jQuery('#btnApprove').hide();
        jQuery('#btnDelete').hide();
        jQuery('#btnCancel').show();
        //jQuery('#btnInvoice').show();

    }
//    Created: 09/10/2014 | Developer: reyro | Description: disable enable inputs when sale canceled

    function fnDisableEnableInputsSaleCanceled() {
        jQuery('#btnCancel').hide();
        jQuery('#btnApprove').hide();
        jQuery('#btnDelete').hide();
        jQuery('#btnSave').hide();
        //jQuery('#SalSalePaymentDeadline').attr("disabled", "disabled").closest('label').addClass('state-disabled');
        //jQuery('#SalSalePaid').attr("disabled", "disabled").closest('label').addClass('state-disabled');
    }

//    Created: 07/10/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var disabled = '';
        var lcState = jQuery("#SalSaleLcState").val();
        //if (lcState === 'APPROVED' || lcState === 'CANCELED') {   //In admin_save there no disables
        //    disabled = 'disabled="disabled"';
        //}
        var buttonHtml = '<button type="button" ' + disabled + ' id="update-' + id + '" class="btn btn-primary btn-xs btnUpdate" title="Editar"><i class="fa fa-pencil"></i></button>';
        buttonHtml += ' <button type="button" ' + disabled + ' id="delete-' + id + '" class="btn btn-danger btn-xs btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 07/10/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function (event) {
                fnDeleteSalesDetails(id); //(id, object)
                event.preventDefault();
            });
        });

        jQuery('.btnUpdate').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            fnReadSaleDetailUpdate(id);
            jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: fills autocomplete with customers employees | Request:Ajax
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
                jQuery('#spanCustomerNit').text(response['nit']);
                jQuery('#spanCustomerNitName').text(response['nitName']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: read products | Request:Ajax
    function fnReadProducts(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProducts',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                fillSelectProducts(response['products'], 'SalSalesDetailInvProductId');
                jQuery('#SalSalesDetailInvProductId').closest('section').show();
                jQuery('#sectionUpdateSaleDetail').hide();
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: fills salePrice and price | Request:Ajax
    function fnReadProductPrices(productId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProductPrices',
            dataType: 'json',
            data: {
                productId: productId
            },
            success: function (response) {
                jQuery('#SalSalesDetailPrice').val(response['lastPrice']);
                jQuery('#SalSalesDetailSalePrice').val(response['lastPrice']);
//                jQuery('#spanMeasure').text(response['measure']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: fills total | Request:Ajax
    function fnReadTotal(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadTotal',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                if (response['total'] === null) {
                    jQuery('#spanTotal').text('0.00');
                    jQuery('#spanDiscount').text('0.00');
                    jQuery('#spanTotalFinal').text('0.00');
                    jQuery('#SalSalePaid').val('0.00');
                } else {
                    jQuery('#spanTotal').text(response['total']);
                    jQuery('#spanDiscount').text(response['discount']);
                    jQuery('#spanTotalFinal').text(response['totalAndDiscount']);
                    jQuery('#SalSalePaid').val(response['paid']);
                    jQuery('#spanDebt').text(response['debt']);
                }

            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: fills total | Request:Ajax
    function fnReadSaleDetailUpdate(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadSaleDetailUpdate',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                jQuery('#SalSalesDetailId').val(id);
                var selectProduct = jQuery('#SalSalesDetailInvProductId');
                selectProduct.closest('section').hide();
                selectProduct.empty();
                selectProduct.append('<option value="' + response[0]['SalSalesDetail']['inv_product_id'] + '" selected="selected"></option>');
                jQuery('#sectionUpdateSaleDetail').text('[' + response[0]['InvProduct']['code'] + '] ' + response[0]['InvProduct']['name']);
                jQuery('#sectionUpdateSaleDetail').show();
                jQuery('#SalSalesDetailInvoiceAlternativeName').val(response[0]['SalSalesDetail']['invoice_alternative_name']);
                jQuery('#SalSalesDetailPrice').val(response[0]['SalSalesDetail']['price']);
                jQuery('#SalSalesDetailSalePrice').val(response[0]['SalSalesDetail']['sale_price']);
                jQuery('#SalSalesDetailQuantity').val(response[0]['SalSalesDetail']['quantity']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro 
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

//    Created: 07/10/2014 | Developer: reyro 
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

//    Created: 07/10/2014 | Developer: reyro | Description: create or update | Request: Ajax
    function fnAdminSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnAdminSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalSaleAdminSaveForm').bittionSerializeObjectJson(),
                customerNit: jQuery('#spanCustomerNit').text(),
                customerNitName: jQuery('#spanCustomerNitName').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    if(jQuery('#SalSaleId').val() == ""){
                        window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']);
                    }
                    jQuery('#SalSaleId').val(response['data']['id']);
                    if (response['data']['system_code'] !== '') {
                        jQuery('#spanSaleTitle').html(': <STRONG>' + response['data']['system_code'] + '</STRONG>');
                    }
                    jQuery('#SalSalePaid').val(response['data']['paid']);
                    //fnHideDeleteButton();
                    fnReadTotal(response['data']['id']);
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }



//    Created: 07/10/2014 | Developer: reyro | Description: create or update, sale and detail | Request: Ajax
    function fnSaveSaleAndDetail() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveSaleAndDetail',
            dataType: 'json',
            data: {
                data: jQuery('#SalSaleAdminSaveForm').bittionSerializeObjectJson(),
                data2: jQuery('#SalSalesDetailAdminSaveForm').bittionSerializeObjectJson(),
                customerNit: jQuery('#spanCustomerNit').text(),
                customerNitName: jQuery('#spanCustomerNitName').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    if(jQuery('#SalSaleId').val() == ""){
                        window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']);
                    }
                    jQuery('#SalSaleId').val(response['data']['id']);
                    if (response['data']['system_code'] !== '') {
                        jQuery('#spanSaleTitle').html(': <STRONG>' + response['data']['system_code'] + '</STRONG>');
                    }
                    fnHideDeleteButton();
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

//    Created: 07/10/2014 | Developer: reyro | Description: create or update | Request: Ajax
    function fnSaveWhenApproved() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveWhenApproved',
            dataType: 'json',
            data: {
                data: jQuery('#SalSaleAdminSaveForm').bittionSerializeObjectJson(),
                customerNit: jQuery('#spanCustomerNit').text(),
                customerNitName: jQuery('#spanCustomerNitName').text()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#SalSalePaid').val(response['data']['paid']);
                    jQuery('#spanDebt').text(response['data']['debt']);
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: deletes sales detail | Request:Ajax
    function fnDeleteSalesDetails(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteSalesDetails',
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