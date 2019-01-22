/* (c)Bittion | Created: 22/09/2014 | Developer:reyro | JS: SalCustomers/create */
jQuery(document).ready(function () {
//START SCRIPT

var SalCustomerId = jQuery('#SalCustomerId');

//************************************** EXEC MAIN - START **************************************
//    Created: 22/09/2014 | Developer: reyro | Description: Validates Save Customer Form
    jQuery("#SalCustomerSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[SalCustomer][email]': {
                email: true
            },
            'data[SalCustomer][nit]':{
                nitUnique: true
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
    
//    Created: 22/09/2014 | Developer: reyro | Description: initial focus
    jQuery('#SalCustomerName').focus();

//    Created: 12/10/2014 | Developer: reyro | Description: .validate extra method for Product Unique 
    jQuery.validator.addMethod('nitUnique', function (value, element) {
        if(value === ''){//because it isn't obligatory
            return true;
        }
        var response = fnVerifyUnique('fnVerifyUniqueNit', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'El NIT ya fue registrado');

//    Created: 22/09/2014 | Developer: reyro | Description: shows modal 
    jQuery('#btnModalCreateEmployee').click(function () {
        if (jQuery('#SalCustomerId').val() === '') {
            alert('Primero debe guardar el cliente');
            return false;
        }
        jQuery('#modalEmployee').modal({show: 'true', backdrop: 'static'});
        jQuery('#SalCustomersEmployeeSalCustomerId').val(jQuery('#SalCustomerId').val());
        jQuery('#modalEmployee').on('shown.bs.modal', function () {
            jQuery('#SalCustomersEmployeeName').focus();
        });
    });

//    Created: 22/09/2014 | Developer: reyro | Description: Always reset form when modal hides    
    $('#modalEmployee').on('hidden.bs.modal', function () {
        jQuery('#SalCustomersEmployeeSaveForm').get(0).reset();
        jQuery('#btnModalCreateEmployee').focus();
    });

//    Created: 22/09/2014 | Developer: reyro | Description: Validates Save Customer Employee Form
    jQuery("#SalCustomersEmployeeSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSaveCustomersEmployee();
        },
        // Rules for form validation
        rules: {
            'data[SalCustomersEmployee][email]': {
                email: true
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

//    Created: 22/09/2014 | Developer: reyro | Description: read customers employees | Request: Ajax
    var table1 = jQuery('#SalCustomersEmployeeIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnReadCustomersEmployee'
        , 'sServerMethod': 'POST'
        , 'bPaginate': false
//        ,"bProcessing": true //not used because there isnt a good position to show it
//        , "iDisplayLength": -1 //doesn't work
//        ,"sDom": 'frt' //shows find processing and table, but it isn't formatted
//        ,"sDom": "<'dt-toolbar'<'col-xs-6'f><'col-xs-6'<'toolbar'>>r>"+
//						"t"
//						"<'dt-toolbar-footer'<'col-xs-6'i><'col-xs-6'p>>"
	, 'sDom': 't'		
//        , 'bInfo': false, //hides all , doesn't work
//            , 'bStateSave': true  //Saves the last search and/or pagination :)
        , "fnServerParams": function (aoData) {
            aoData.push(
                    {name: "customerId", value: jQuery('#SalCustomerId').val()}
            );
        }
        , 'fnCreatedRow': function (nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[4]);
            jQuery('td:eq(4)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function (oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [4]} //disable active and buttons columns
        ]
//        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 22/09/2014 | Developer: reyro | Description: create or update | Request: Ajax
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalCustomerSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    fillAutoComplete(response['data']['autocomplete']['sectors'], 'listSector');
                    jQuery('#SalCustomerId').val(response['data']['id']);
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 22/09/2014 | Developer: reyro | Description: create or update | Request: Ajax
    function fnSaveCustomersEmployee() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveCustomersEmployee',
            dataType: 'json',
//            async: false,
            data: {
                data: jQuery('#SalCustomersEmployeeSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    table1.draw();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
                jQuery('#modalEmployee').modal('hide');
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalEmployee').modal('hide');
            }
        });
    }

//    Created: 22/09/2014 | Developer: reyro | Description: deletes customers employee | Request:Ajax
    function fnDeleteCustomersEmployee(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteCustomersEmployee',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    table1.draw();
                }else{
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }	

//    Created: 22/09/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var buttonHtml = '<button type="button" id="update-' + id + '" class="btn btn-primary btn-xs btnUpdate" title="Editar"><i class="fa fa-pencil"></i></button>';
        buttonHtml += ' <button type="button" id="delete-' + id + '" class="btn btn-danger btn-xs btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 22/09/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function (event) {
                fnDeleteCustomersEmployee(id); //(id, object)
                event.preventDefault();
            });
        });
        
        jQuery('.btnUpdate').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            var row = jQuery(this).closest("tr");
            jQuery('#SalCustomersEmployeeId').val(id);
            jQuery('#SalCustomersEmployeeName').val(row.find('td:first').html());
            jQuery('#SalCustomersEmployeePhone').val(row.find('td').eq(1).html());
            jQuery('#SalCustomersEmployeeEmail').val(row.find('td').eq(2).html());
            jQuery('#SalCustomersEmployeeJobTitle').val(row.find('td').eq(3).html());

            jQuery('#modalEmployee').modal({show: 'true', backdrop: 'static'});
            jQuery('#SalCustomersEmployeeSalCustomerId').val(jQuery('#SalCustomerId').val());
            jQuery('#modalEmployee').on('shown.bs.modal', function () {
                jQuery('#SalCustomersEmployeeName').focus();
            });

        });
    }

//    Created: 22/09/2014 | Developer: reyro 
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
    
//    Created: 12/10/2014 | Developer: reyro | Description: Universal verify unique function used for email | Request: Ajax
    function fnVerifyUnique(functionName, value) {
        var res = 0;
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + functionName,
            dataType: 'json',
            async: false, //the key for jquery.validation plugin, if it's true it finishes the function rigth there and won't work
            data: {
                value: value,
                id: SalCustomerId.val()
            },
            success: function (response) {
                res = response;
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
        return res;
    }    
//END SCRIPT
});