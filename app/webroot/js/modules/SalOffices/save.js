/* (c)Bittion | Created: 15/01/2015 | Developer:reyro | JS: SalOffices/update */
jQuery(document).ready(function () {
//START SCRIPT

    var SalOfficeId = jQuery('#SalOfficeId');

    jQuery('#SalOfficeInvoiceName').keyup(function (e) {
        this.value = this.value.toUpperCase();
    });

//************************************** EXEC MAIN - START **************************************
//    Created: 15/01/2015 | Developer: reyro | Description: Validates Update Form
    jQuery("#SalOfficeSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {},
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

    jQuery('#SalOfficeName').focus();
//************************************** EXEC MAIN - END **************************************

//    Created: 15/01/2015 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalOfficeSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    SalOfficeId.val(response['data']['id']);
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