/* (c)Bittion | Created: 18/09/2014 | Developer:reyro | JS: InvProducts/save */
jQuery(document).ready(function () {
//START SCRIPT

    var InvProductId = jQuery('#InvProductId');

//************************************** EXEC MAIN - START **************************************
//    Created: 18/09/2014 | Developer: reyro | Description: Validates Update Form
    jQuery("#InvProductSaveServiceForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[InvPrice][0][price]': {
                required: true,
                number: true
            },
            'data[InvProduct][code]': {
                codeUnique: true
            }
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

//    Created: 12/10/2014 | Developer: reyro | Description: .validate extra method for Product Unique 
    jQuery.validator.addMethod('codeUnique', function (value, element) {
        var response = fnVerifyUnique('fnVerifyUniqueCode', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'El codigo ya fue registrado');

    jQuery('#InvProductCode').focus();
    
    jQuery('#InvProductCode').keyup(function(e){
       this.value = this.value.toUpperCase();
    });
//    jQuery('#InvProductMeasure').keyup(function(e){
//       this.value = this.value.toUpperCase();
//    });
//************************************** EXEC MAIN - END **************************************

//    Created: 18/09/2014 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveService',
            dataType: 'json',
            data: {
                data: jQuery('#InvProductSaveServiceForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    if(InvProductId.val() == ""){
                        window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']);
                    }
                    //fillAutoComplete(response['data']['measures'], 'listMeasure');
                    InvProductId.val(response['data']['id']);
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 12/10/2014 | Developer: reyro 
    function fillAutoComplete(data, id) {
        var autocomplete = jQuery('#' + id);
        //empty current autocomplete values
        autocomplete.empty();
        //put an empty/message choice
        autocomplete.append('<option value="" disabled="disabled" autocompleteed="autocompleteed">Elija un controlador</option>');
        //fill the autocomplete with new data  
        $.each(data, function (index, value) {
            autocomplete.append('<option value="'
                    + index
                    + '">'
                    + value
                    + '</option>');
        });

    }

//    Created: 12/10/2014 | Developer: reyro | Description: Universal verify unique function used for email | Request: Ajax
    function fnVerifyUnique(functionName, value) {
        var res = 0;
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + functionName,
            dataType: 'json',
            async: false, //the key for jquery.validation plugin, if it's true it finishes the function rigth there and won't work
            data: {
                value: value,
                id: InvProductId.val()
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

//END SCRIPT
});