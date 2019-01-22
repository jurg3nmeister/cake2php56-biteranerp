/* (c)Bittion Admin Module | Created: 18/08/2014 | Developer:reyro | JS: AdmActions/create */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 18/08/2014 | Developer: reyro | Description: Validates Create Form
    jQuery('#AdmActionCreateForm').validate({
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
//            'data[AdmAction][email]': {
//                required: 'Please enter your email address',
//                email: 'Please enter a VALID email address'
//            },
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 18/08/2014 | Developer: reyro | Description: event select change for get controllers avaliable
    jQuery('#AdmModuleId').change(function() {
        fnReadControllers(jQuery(this).val());
    });
//    Created: 18/08/2014 | Developer: reyro | Description: event select change for get controllers avaliable
    jQuery('#AdmActionAdmControllerId').change(function() {
        fnReadActionsAvailable(jQuery(this).val(), jQuery("#AdmActionAdmControllerId option:selected").text());
    });
//************************************** EXEC MAIN - END **************************************
//    Created: 18/08/2014 | Developer: reyro | Description: get controllers avaliable for HTML select | Request: Ajax
    function fnReadControllers(moduleId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadControllers',
//            async: false,
            dataType: 'json',
            data: {
                moduleId: moduleId
            },
            success: function(response) {
                var select = jQuery('#AdmActionAdmControllerId');
                select.empty();
                select.append('<option value="" disabled="disabled" selected="selected">Elija un controlador</option>');
                $.each(response, function(index, value) {
                    select.append('<option value="'+ index + '">' + value + '</option>');
                });
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }
    
//    Created: 18/08/2014 | Developer: reyro | Description: get actions avaliable for HTML select | Request: Ajax
    function fnReadActionsAvailable(controllerId, controllerName) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadActionsAvailable',
//            async: false,
            dataType: 'json',
            data: {
                controllerId: controllerId,
                controllerName: controllerName
            },
            success: function(response) {
                var select = jQuery('#AdmActionName');
                select.empty();
                select.append('<option value="" disabled="disabled" selected="selected">Elija una acción</option>');
                $.each(response, function(index, value) {
                    select.append('<option value="'+ value + '">' + value + '</option>');
                });
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 18/08/2014 | Developer: reyro | Description: Function Create a new User | Request:Ajax
    function fnCreate() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreate',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmActionCreateForm').bittionSerializeObjectJson()
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    var selectedVal = jQuery('#AdmActionName').val();
                    jQuery('#AdmActionName option[value='+ selectedVal +']').remove();
                    jQuery('#AdmActionDescription').val('');
//                    jQuery('#AdmActionCreateForm').get(0).reset();
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