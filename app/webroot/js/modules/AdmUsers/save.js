/* (c)Bittion Admin Module | Created: 05/08/2014 | Developer:reyro | JS: AdmUsers/update */
jQuery(document).ready(function () {
//START SCRIPT

//************************************** EXEC MAIN - START **************************************

//    Created: 08/01/2015 | Developer: reyro | Description: Check if exist to block username change
    if(jQuery('#AdmUserId').val() !== ''){ // update
        jQuery('#AdmUserUsername').prop('disabled', true);
        jQuery('#linkGenerateUsername').hide();
    }

//    Created: 08/01/2015 | Developer: reyro | Description: initiate datepicker birthdate (setup with years)
    var initiBirthYear = new Date().getFullYear() - 21;
    jQuery('#AdmProfileBirthdate').datepicker({
        dateFormat: 'dd/mm/yy',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>',
        changeMonth: true,
        changeYear: true,
        defaultDate: new Date(initiBirthYear, 0, 1)
    });

//    Created: 08/01/2015 | Developer: reyro | Description: event click for reset a user's password
    jQuery('#btnResetPassword').click(function () {
        showBittionAlertModal({
            content: '¿Está seguro de resetear la contraseña para este usuario?',
            title: 'Resetear Contraseña'
        });
        jQuery('#bittionBtnYes').click(function (event) {
            hideBittionAlertModal();
            event.preventDefault();
            fnResetPassword();
        });
    });

//    Created: 08/01/2015 | Developer: reyro | Description: .validate extra method for validate date (dd/mm/yyyy)
    $.validator.addMethod(
        "date",
        function (value, element) {
            var bits = value.match(/([0-9]+)/gi), str;
            if (!bits)
                return this.optional(element) || false;
            str = bits[1] + '/' + bits[0] + '/' + bits[2];
            return this.optional(element) || !/Invalid|NaN/.test(new Date(str));
        },
        "Por favor ingrese una fecha en formato dd/mm/yyyy"
    );

//    Created: 08/01/2015 | Developer: reyro | Description: validates Account User Form
    jQuery("#AdmUserSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[AdmProfile][email]': {
                email: true,
                emailUnique: true
            },
            'data[AdmProfile][given_name]': {
                minlength: 3
            },
            'data[AdmProfile][family_name]': {
                minlength: 3
            },
            'data[AdmProfile][birthdate]': {
                date: true
            }
        },
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 08/01/2015 | Developer: reyro | Description: .validate extra method for Email Unique 
    jQuery.validator.addMethod('emailUnique', function (value, element) {
        //Extra step validation for Update
        if (jQuery('#emailHidden').length > 0) {
            if (jQuery('#emailHidden').val() === value) {
                return true;
            }
        }
        ///////
        var response = fnVerifyUnique('fnVerifyUniqueEmail', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'El correo electrónico ya fue registrado');

//************************************** EXEC MAIN - END **************************************

//    Created: 08/01/2015 | Developer: reyro | Description: Universal verify unique function used for email | Request: Ajax
    function fnVerifyUnique(functionName, value) {
        var res = 0;
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + functionName,
            dataType: 'json',
            async: false, //the key for jquery.validation plugin, if it's true it finishes the function rigth there and won't work
            data: {
                value: value
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

//    Created: 08/01/2015 | Developer: reyro | Description: reset user's password | Request: Ajax
    function fnResetPassword() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnResetPassword',
//            async: false,
            dataType: 'json',
            data: {
                id: jQuery('#AdmUserId').val()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#content').prepend('<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">×</a><strong>NUEVA CONTRASEÑA: </strong> ' + response['data'] + '</div>');
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }


//    Created: 08/01/2015 | Developer: reyro | Description: Link Event click for generate a Username
    jQuery('#linkGenerateUsername').click(function (event) {

        if(jQuery('#AdmUserId').val() !== ''){ //If already exist
            return false;
        }

        var given_name = jQuery('#AdmProfileGivenName').val();
        var family_name = jQuery('#AdmProfileFamilyName').val();
        if (given_name === '' || family_name === '') {
            alert('Los campos Nombre(s) y Apellido(s) deben estar llenados');
        } else {
            fnGenerateUsername(given_name, family_name);
        }
        event.preventDefault();

    });

//    Created: 08/01/2015 | Developer: reyro | Description: Generate username | Request: Ajax
    function fnGenerateUsername(given_name, family_name) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnGenerateUsername',
            dataType: 'json',
            data: {given_name: given_name, family_name: family_name
            },
            success: function(response) {
                jQuery('#AdmUserUsername').val(response);
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }


//    Created: 08/01/2015 | Developer: reyro | Description: create and update user/profile | Request: Ajax
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#AdmUserSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#AdmUserId').val(response['data']['userId']);
                    jQuery('#emailHidden').val(response['data']['email']);
                    jQuery('#AdmUserUsername').prop('disabled', true);
                    jQuery('#linkGenerateUsername').hide();
                    if(response['data']['password'] !== ''){
                        jQuery('#content').prepend('<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">×</a>' + ' <strong>CONTRASEÑA</strong>: ' + response['data']['password'] + '</div>');
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


//END SCRIPT
});


