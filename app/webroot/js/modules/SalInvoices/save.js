/* (c)Bittion | Created: 17/10/2014 | Developer:reyro | JS: SalInvoices/update */
jQuery(document).ready(function () {
//START SCRIPT

    var SalInvoiceId = jQuery('#SalInvoiceId');

//************************************** EXEC MAIN - START **************************************
//    Created: 17/10/2014 | Developer: reyro | Description: Validates Update Form
    jQuery("#SalInvoiceSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[SalInvoice][tax_number]':{
              digits:true  
            },
            'data[SalInvoice][valid_days]':{
                digits:true
            },
            'data[SalInvoice][authorization_number]':{
                digits:true  ,
                authorizationNumberUnique:true
            },
            'data[SalInvoice][control_key]':{
                controlKeyUnique:true
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

    //    Created: 17/10/2014 | Developer: reyro | Description: initiate datepicker
    jQuery('#SalInvoiceStartDate').datepicker({
        dateFormat: 'dd/mm/yy',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });

    jQuery('#SalInvoiceTaxName').focus();
    
    jQuery('#SalInvoiceTaxName').keyup(function(e){
       this.value = this.value.toUpperCase();
    });
    jQuery('#SalInvoiceLegalRepresentative').keyup(function(e){
       this.value = this.value.toUpperCase();
    });
    jQuery('#SalInvoiceMainActivity').keyup(function(e){
       this.value = this.value.toUpperCase();
    });
    //jQuery('#SalInvoiceControlKey').keyup(function(e){
    //   this.value = this.value.toUpperCase();
    //});
//************************************** EXEC MAIN - END **************************************

//    Created: 17/10/2014 | Developer: reyro | Description: .validate extra method 
    jQuery.validator.addMethod('authorizationNumberUnique', function (value, element) {
        var response = fnVerifyUnique('fnAuthorizationNumberUnique', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'El número de autorización ya fue registrado');    
    
//    Created: 17/10/2014 | Developer: reyro | Description: .validate extra method 
    jQuery.validator.addMethod('controlKeyUnique', function (value, element) {
        var response = fnVerifyUnique('fnControlKeyUnique', value);
        if (response > 0) {
            return false;
        }
        return true;
    }, 'La llave de control ya fue registrada');    

//    Created: 17/10/2014 | Developer: reyro | Description: Universal verify unique | Request: Ajax
    function fnVerifyUnique(functionName, value) {
        var res = 0;
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + functionName,
            dataType: 'json',
            async: false, //the key for jquery.validation plugin, if it's true it finishes the function rigth there and won't work
            data: {
                value: value,
                id: SalInvoiceId.val()
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

//    Created: 17/10/2014 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalInvoiceSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    SalInvoiceId.val(response['data']['id']);
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