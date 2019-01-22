/* (c)Bittion Admin Module | Created: 21/08/2014 | Developer:reyro | JS: AdmRolesTransactions/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 21/08/2014 | Developer: reyro | Description: Validates Form
    jQuery('#AdmRolesTransactionIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreate();
        },
        // Rules for form validation
        rules: {
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 21/08/2014 | Developer: reyro | Description: event select change for get actions
    jQuery('#AdmModuleId').change(function() {
        if (jQuery('#AdmModuleId').val() !== null && jQuery('#AdmRolesTransactionAdmRoleId').val() !== null) {
            fnRead();
        }
    });

//    Created: 21/08/2014 | Developer: reyro | Description: event select change for get actions
    jQuery('#AdmRolesTransactionAdmRoleId').change(function() {
        if (jQuery('#AdmModuleId').val() !== null && jQuery('#AdmRolesTransactionAdmRoleId').val() !== null) {
            fnRead();
        }
    });

//************************************** EXEC MAIN - END **************************************
//    Created: 21/08/2014 | Developer: reyro | Description: get actions for a module and a role | Request: Ajax
    function fnRead() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnRead',
//            async: false,
            dataType: 'json',
            data: {
                moduleId: jQuery('#AdmModuleId').val(),
                roleId: jQuery('#AdmRolesTransactionAdmRoleId').val()
            },
            success: function(response) {
                if (response !== undefined) {
                    $('button').prop('disabled', false);
                } else {
                    $('button').prop('disabled', true);
                }
                var table = fnGenerateHtmlTable(response);
                jQuery('#tblCheckboxesContainer').html(table);
                fnBindTableEvents();
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 21/08/2014 | Developer: reyro | Description: insert and deletes actions selected | Request:Ajax
    function fnCreate() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreate',
//            async: false,
            beforeSend: function() {
                jQuery('#btnSave').button('loading');
            },
            dataType: 'json',
            data: jQuery('#AdmRolesTransactionIndexForm').bittionSerializeObjectJson(),
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#btnSave').button('reset');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#btnSave').button('reset');
            }
        });
    }

//    Created: 21/08/2014 | Developer: reyro | Description: Craete table with actions checkboxes | Request:Ajax
    function fnGenerateHtmlTable(data) {
        var html = '<table id="tblCheckboxes" class="table table-bordered table-striped table-condensed smart-form has-tickbox">\n\
                    <thead><tr>\n\
                        <th><label class="checkbox"><input type="checkbox" id="chkMain" name="checkbox-inline"><i></i></label></th>\n\
                        <th class="col-md-2">Controlador</th>\n\
                        <th>Transacciones</th>\n\
                    </tr></thead>';
        html += '<tbody>';
        html += fnGenerateHtmlTbody(data);
        html += '</tbody></table>';

        return html;
    }

//    Created: 21/08/2014 | Developer: reyro | Description: Creates Html Tbody with checkboxes for table actions
    function fnGenerateHtmlTbody(data) {
        var html = '';
        $.each(data, function(controllerName, controllerObject) {
            html += '<tr>';
            html += '<td><label class="checkbox"><input type="checkbox" class="chkController" name="checkbox-inline"><i></i></label></td>';
            html += '<td>' + controllerName + '</td>';
            html += '<td><div class="inline-group">';
            $.each(controllerObject, function(transactionName, transactionObject) {
                var checked = '';
                if (transactionObject['checked'] === true) {
                    checked = 'checked = "checked"';
                }
                html += '<label class="checkbox"><input type="checkbox" class="chkTransaction" value="' + transactionObject['transactionId'] + '" name="transactionsIds[]" ' + checked + '><i></i>' + transactionObject['transactionName'] + '</label>';
            });
            html += '</div></td>';
            html += '</tr>';
        });
        return html;
    }

//    Created: 21/08/2014 | Developer: reyro | Description: checkboxes click events
    function fnBindTableEvents() {
        $("#chkMain").on('click', function() {
            if (this.checked) {
//                $('#tblCheckboxes input[name="transactionsIds[]"]').prop('checked', true);
                $('#tblCheckboxes .chkTransaction').prop('checked', true);
                $('#tblCheckboxes .chkController').prop('checked', true);
            } else {
//                $('#tblCheckboxes input[name="transactionsIds[]"]').prop('checked', false);
                $('#tblCheckboxes .chkTransaction').prop('checked', false);
                $('#tblCheckboxes .chkController').prop('checked', false);
            }
        });

        $('#tblCheckboxes tbody .chkController').on('click', function() {
            if (this.checked) {
                $(this).closest('tr').find('.chkTransaction').prop('checked', true);
            } else {
                $(this).closest('tr').find('.chkTransaction').prop('checked', false);
            }
        });

        $('#tblCheckboxes tbody .chkTransaction').on('click', function() {
            var currentTr = $(this).closest('tr');
            if (currentTr.find('.chkTransaction:checked').length === 0) {
                currentTr.find('.chkController').prop('checked', false);
            }
            if (currentTr.find('.chkTransaction:checked').length === 1) {
                currentTr.find('.chkController').prop('checked', true);
            }

        });
    }

//END SCRIPT
});