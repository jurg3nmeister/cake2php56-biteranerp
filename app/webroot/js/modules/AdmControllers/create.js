/* (c)Bittion Admin Module | Created: 15/08/2014 | Developer:reyro | JS: AdmControllers/create */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 15/08/2014 | Developer: reyro | Description: Validates Create Form
    jQuery('#AdmControllerCreateForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreate();
        },
        // Rules for form validation
        rules: {
//			'data[AdmProfile][given_name]': {
//                minlength: 3
//            },            			
        },
        // Messages for form validation
        messages: {
//            'data[AdmController][email]': {
//                required: 'Please enter your email address',
//                email: 'Please enter a VALID email address'
//            },
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 15/08/2014 | Developer: reyro | Description: event select change for get controllers avaliable
    jQuery('#AdmModuleInitials').change(function() {
        fnReadControllersAvaliable(jQuery(this).val());
//alert(this.id);
    });
//************************************** EXEC MAIN - END **************************************
//    Created: 15/08/2014 | Developer: reyro | Description: get controllers avaliable | Request: Ajax
    function fnReadControllersAvaliable(moduleInitials) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadControllersAvaliable',
//            async: false,
            dataType: 'json',
            data: {
                moduleIntials: moduleInitials
            },
            success: function(response) {
                var select = jQuery('#AdmControllerName');
                select.empty();
                select.append('<option value="" disabled="disabled" selected="selected">Elija un controlador</option>');
                $.each(response, function(index, value) {
                    select.append('<option value="'+ value + '">' + value + '</option>');
                });
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 15/08/2014 | Developer: reyro | Description: Function Create a new User | Request:Ajax | Obs: 2 choices: 'create' and 'create and edit'
    function fnCreate() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreate',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmControllerCreateForm').bittionSerializeObjectJson()
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    var selectedVal = jQuery('#AdmControllerName').val();
                    jQuery('#AdmControllerName option[value='+ selectedVal +']').remove();
                    jQuery('#AdmControllerDescription').val('');
//                    jQuery('#AdmControllerCreateForm').get(0).reset();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//END SCRIPT
});