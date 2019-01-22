/* (c)Bittion | Created: 07/01/2015 | Developer:reyro | JS: AdmParameters/save */
jQuery(document).ready(function () {
//START SCRIPT

var AdmParameterId = jQuery('#AdmParameterId');

//************************************** EXEC MAIN - START **************************************
//    Created: 07/01/2015 | Developer: reyro | Description: Validates Update Form
    jQuery("#AdmParameterSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });
    
    jQuery('#AdmParameterName').focus();
//************************************** EXEC MAIN - END **************************************

//    Created: 07/01/2015 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#AdmParameterSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    AdmParameterId.val(response['data']['id']);
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