/* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro | JS: AdmPeriods/create */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 19/08/2014 | Developer: reyro | Description: Validates Create Form
    jQuery("#AdmPeriodCreateForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreate();
        },
        // Rules for form validation
        rules: {
            'data[AdmPeriod][name]': {
                digits: true,
                minlength: 4,
                periodUnique:true
            },
        },
        // Messages for form validation
        messages: {
            'data[AdmPeriod][name]': {
                minlength: 'Ingrese 4 digitos'
            },
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 19/08/2014 | Developer: reyro | Description: .validate extra methos verify unique username
    jQuery.validator.addMethod('periodUnique', function(value, element) {
        var response = fnVerifyUnique('fnVerifyUniquePeriod', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'La gestión ya fue creada anteriormente');

//************************************** EXEC MAIN - END **************************************

//    Created: 19/08/2014 | Developer: reyro | Description: Function Create a new User | Request:Ajax
    function fnCreate() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreate',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmPeriodCreateForm').bittionSerializeObjectJson(),
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    jQuery("#AdmPeriodCreateForm").get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

    //    Created: 08/08/2014 | Developer: reyro | Description: Universal verify unique function | Request: Ajax
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
            success: function(response) {
                res = response;
            },
            error: function(response, status, error) {
//                bittionShowGrowlMessage('error', 'ajaxError');
                bittionAjaxErrorHandler(response, status, error);
            }
        });
        return res;
    }

//END SCRIPT
});