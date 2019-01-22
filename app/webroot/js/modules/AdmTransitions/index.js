/* (c)Bittion Admin Module | Created: 21/08/2014 | Developer:reyro | JS: AdmTransitions/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
    jQuery('#selectController').prop('selectedIndex', 0);
    jQuery('#selectController').select2();
    jQuery('#selectController').change(function() {
        fnRead(jQuery(this).val());
    });
    jQuery('#btnCreateTransition').click(function() {
        jQuery('#modalTransition').modal({show: 'true', backdrop: 'static'});
        jQuery('#modalTransition').on('shown.bs.modal', function() {
            $('#AdmTransitionAdmStateId').focus();
        });
    });
    jQuery('#btnCreateState').click(function() {
        jQuery('#modalState').modal({show: 'true', backdrop: 'static'});
        jQuery('#modalState').on('shown.bs.modal', function() {
            $('#AdmStateName').focus();
        });
    });
    jQuery('#btnCreateTransaction').click(function() {
        jQuery('#modalTransaction').modal({show: 'true', backdrop: 'static'});
        jQuery('#modalTransaction').on('shown.bs.modal', function() {
            $('#AdmTransactionName').focus();
        });
    });
    fnRead(jQuery('#selectController').val());

    //    Created: 22/08/2014 | Developer: reyro | Description: Validates transition form
    jQuery('#AdmTransitionIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreateTransition();
        },
        // Rules for form validation
        rules: {},
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    //    Created: 22/08/2014 | Developer: reyro | Description: Validates state form
    jQuery('#AdmStateIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreateState();
        },
        // Rules for form validation
        rules: {},
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    //    Created: 22/08/2014 | Developer: reyro | Description: Validates transaction form
    jQuery('#AdmTransactionIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreateTransaction();
        },
        // Rules for form validation
        rules: {},
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 22/08/2014 | Developer: reyro | Description: Function create transition | Request:Ajax
    function fnCreateTransition() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreateTransition',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmTransitionIndexForm').bittionSerializeObjectJson()},
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                    jQuery('#AdmTransitionIndexForm').get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#modalTransition').modal('hide');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalTransition').modal('hide');
            }
        });
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function create state | Request:Ajax
    function fnCreateState() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreateState',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmStateIndexForm').bittionSerializeObjectJson(), controllerId: jQuery('#selectController').val()},
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                    jQuery('#AdmStateIndexForm').get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#modalState').modal('hide');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalState').modal('hide');
            }
        });
    }
//    Created: 22/08/2014 | Developer: reyro | Description: Function create transaction | Request:Ajax
    function fnCreateTransaction() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreateTransaction',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmTransactionIndexForm').bittionSerializeObjectJson(), controllerId: jQuery('#selectController').val()},
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                    jQuery('#AdmTransactionIndexForm').get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#modalTransaction').modal('hide');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalTransaction').modal('hide');
            }
        });
    }

    //    Created: 21/08/2014 | Developer: reyro | Description: fill transitions, states and transactions tables | Request:Ajax
    function fnRead(controllerId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnRead',
            dataType: 'json',
            data: {
                controllerId: controllerId
            },
            success: function(response) {
                var tbodyTransitions = fnFillTbodyTransitions(response['Transitions']);
                var tbodyStates = fnFillTbodyStates(response['States']);
                var tbodyTransactions = fnFillTbodyTransactions(response['Transactions']);
                jQuery('#AdmTransitionTable tbody').html(tbodyTransitions);
                jQuery('#AdmStateTable tbody').html(tbodyStates);
                jQuery('#AdmTransactionTable tbody').html(tbodyTransactions);
                fnFillSelect('AdmTransitionAdmStateId', 'Elija un estado', response['States']);
                fnFillSelect('AdmTransitionAdmTransactionId', 'Elija una transaccion', response['Transactions']);
                fnFillSelect('AdmTransitionAdmFinalStateId', 'Elija un estado', response['States']);
                fnBindTablesEvents();
            }, error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 22/08/2014 | Developer: reyro | Description: deletes transitions | Request:Ajax
    function fnDeleteTransition(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteTransition',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }
//    Created: 22/08/2014 | Developer: reyro | Description: deletes states | Request:Ajax
    function fnDeleteState(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteState',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 22/08/2014 | Developer: reyro | Description: deletes transactions | Request:Ajax
    function fnDeleteTransaction(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteTransaction',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead(jQuery('#selectController').val());
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 18/08/2014 | Developer: reyro | Description: bind events to tables, to delete button
    function fnBindTablesEvents() {
        jQuery('#AdmTransitionTable .btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar esta transición?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnDeleteTransition(id); //(id, object)
                event.preventDefault();
            });
        });
        jQuery('#AdmStateTable .btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar este estado?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnDeleteState(id); //(id, object)
                event.preventDefault();
            });
        });
        jQuery('#AdmTransactionTable .btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar esta transacción?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnDeleteTransaction(id); //(id, object)
                event.preventDefault();
            });
        });
        fnUpdateXEditableState();
        fnUpdateXEditableTransaction();
    }

//    Created: 22/08/2014 | Developer: reyro | Description: update state description directly on table with plugin x-editable       
    function fnUpdateXEditableState() {
        jQuery('#AdmStateTable .xEditState').editable({
            type: 'text',
            url: bittionUrlProjectAndController + 'fnUpdateXEditableState',
//            title: 'Descripción',
            validate: function(value) {
                if (jQuery.trim(value) === '')
                    return 'No puede estar vacio';
            },
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            success: function(response, newValue) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                fnRead(jQuery('#selectController').val());
            }
        });
    }
//    Created: 22/08/2014 | Developer: reyro | Description: update transaction description directly on table with plugin x-editable   
    function fnUpdateXEditableTransaction() {
        jQuery('#AdmTransactionTable .xEditTransaction').editable({
            type: 'text',
            url: bittionUrlProjectAndController + 'fnUpdateXEditableTransaction',
            title: 'Descripción',
            validate: function(value) {
                if (jQuery.trim(value) === '')
                    return 'No puede estar vacio';
            },
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            success: function(response, newValue) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                fnRead(jQuery('#selectController').val());
            }
        });
    }

//    Created: 22/08/2014 | Developer: reyro | Description: fill Html select
    function fnFillSelect(idSelect, message, data) {
        var select = jQuery('#' + idSelect);
        //empty current select values
        select.empty();
        //put an empty/message choice
        select.append('<option value="" disabled="disabled" selected="selected">' + message + '</option>');
        //fill the select with new data  
        jQuery.each(data, function(index, value) {
            select.append('<option value="'
                    + value['id'] + '">'
                    + value['name']
                    + '</option>');
        });
    }

    function fnFillTbodyTransitions(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<tr>';
            html += '<td>' + value['initialState'] + '</td>';
            html += '<td>' + value['transaction'] + '</td>';
            html += '<td>' + value['finalState'] + '</td>';
            html += '<td>' + '<button type="button" id="deleteTransition-' + value['id'] + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>' + '</td>';
            html += '</tr>';
        });
        return html;
    }

    function fnFillTbodyStates(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<tr>';
            html += '<td><a href="#" id="name-' + value['id'] + '" class="xEditState" title="Nombre" data-pk="' + value['id'] + '">' + value['name'] + '</a></td>';
            html += '<td><a href="#" id="description-' + value['id'] + '" class="xEditState" title="Descripción" data-pk="' + value['id'] + '">' + value['description'] + '</a></td>';
//            html += '<td>' + value['description'] + '</td>';
            html += '<td>' + '<button type="button" id="deleteState-' + value['id'] + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>' + '</td>';
            html += '</tr>';
        });
        return html;
    }
    function fnFillTbodyTransactions(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<tr>';
            html += '<td><a href="#" id="name-' + value['id'] + '" class="xEditTransaction" data-pk="' + value['id'] + '">' + value['name'] + '</a></td>';
            html += '<td><a href="#" id="description-' + value['id'] + '" class="xEditTransaction" data-pk="' + value['id'] + '">' + value['description'] + '</a></td>';
            html += '<td>' + '<button type="button" id="deleteTransaction-' + value['id'] + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>' + '</td>';
            html += '</tr>';
        });
        return html;
    }

//END SCRIPT
});
