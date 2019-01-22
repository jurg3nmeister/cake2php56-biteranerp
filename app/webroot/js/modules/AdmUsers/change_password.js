/* (c)Bittion Admin Module | Created: 01/09/2014 | Developer:reyro | JS: AdmUsers/change_password */
jQuery(document).ready(function() {
//START SCRIPT

//************************************** EXEC MAIN - START **************************************
//    Created: 01/09/2014 | Developer: reyro | Description: validates Create Form
    jQuery("#AdmUserChangePasswordForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnUpdatePassword();
        },
        // Rules for form validation
        rules: {
            'data[AdmUser][current_password]': {
                required: true,
                minlength: 6,
                validateCurrentPassword: true
            },
            'data[AdmUser][password]': {
                required: true,
                minlength: 6
            },
            'data[AdmUser][password_confirm]': {
                required: true,
                minlength: 6,
                equalTo: "#AdmUserPassword"
            }
        },
        // Messages for form validation
        messages: {
//            'data[AdmUser][email]': {
//                required: 'Please enter your email address',
//                email: 'Please enter a VALID email address'
//            },
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 01/09/2014 | Developer: reyro | Description: .validate extra method for validate current password
    jQuery.validator.addMethod('validateCurrentPassword', function(value, element) {
        if (fnValidateCurrentPassword(value) === 0) {
            return false;
        }
        return true;
    }, 'Contraseña actual incorrecta');
//************************************** EXEC MAIN - END **************************************

//    Created: 01/09/2014 | Developer: reyro | Description: validate current password | Request: Ajax | Obs: the userId is used in the controller with a session
    function fnValidateCurrentPassword(currentPassword) {
        var res = 0;
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnValidateCurrentPassword',
            dataType: 'json',
            async: false, //the key for jquery.validation plugin, if it's true it finishes the function rigth there and won't work
            data: {
                currentPassword: currentPassword
            },
            success: function(response) {
                res = response;
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
        return res;
    }

//    Created: 01/09/2014 | Developer: reyro | Description: Updates password | Request:Ajax 
    function fnUpdatePassword() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnUpdatePassword',
            dataType: 'json',
            data: {currentPassword: jQuery('#AdmUserCurrentPassword').val(), password:jQuery('#AdmUserPassword').val()},
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery("#AdmUserChangePasswordForm").get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//END SCRIPT
});