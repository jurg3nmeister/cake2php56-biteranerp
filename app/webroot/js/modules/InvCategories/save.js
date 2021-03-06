/* (c)Bittion | Created: 12/10/2014 | Developer:reyro | JS: InvCategories/update */
jQuery(document).ready(function () {
//START SCRIPT

var InvCategoryId = jQuery('#InvCategoryId');

//************************************** EXEC MAIN - START **************************************
//    Created: 12/10/2014 | Developer: reyro | Description: Validates Update Form
    jQuery("#InvCategorySaveForm").validate({
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
    
    jQuery('#InvCategoryName').focus();
//************************************** EXEC MAIN - END **************************************

//    Created: 12/10/2014 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#InvCategorySaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    InvCategoryId.val(response['data']['id']);
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