/* (c)Bittion | Created: 21/02/2015 | Developer:reyro | JS: SalInvoices/update */
jQuery(document).ready(function () {
//START SCRIPT

    var SalInvoiceId = jQuery('#SalInvoiceId');

//************************************** EXEC MAIN - START **************************************
//    Created: 21/02/2015 | Developer: reyro | Description: Validates Update Form
    jQuery("#SalInvoiceActivationForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnActivateInvoice();
        },
        // Rules for form validation
        rules: {
            'data[SalInvoice][nit]':{
                required:true,
                digits:true
            },
            'data[SalInvoice][invoice_number]':{
                required:true,
                digits:true
            },
            'data[SalInvoice][date]':{
                required:true
                //date:true
            },
            'data[SalInvoice][total]':{
                required:true,
                number: true
            },
            'data[SalInvoice][authorization_number]':{
                digits:true
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

    //NOT WORKING because it only validates yy-mm-dd
    //    Created: 21/02/2015 | Developer: reyro | Description: initiate datepicker
    //jQuery('#SalInvoiceDate').datepicker({
    //    dateFormat: 'dd/mm/yy',
    //    prevText: '<i class="fa fa-chevron-left"></i>',
    //    nextText: '<i class="fa fa-chevron-right"></i>'
    //});

    jQuery('#SalInvoiceNit').focus();

    jQuery('#btnClear').click(function(event){
        fnClearForm();
        event.preventDefault();
    });
//************************************** EXEC MAIN - END **************************************


//    Created: 21/02/2015 | Developer: reyro | Request:Ajax
    function fnActivateInvoice() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnActivateInvoice',
            dataType: 'json',
            data: {
                data: jQuery('#SalInvoiceActivationForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    jQuery('#content').prepend('<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">×</a><strong>CÓDIGO DE CONTROL: </strong> ' + response['data']['controlCode'] + '</div>');
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }


//    Created: 21/02/2015 | Developer: reyro | Request:Ajax
    function fnClearForm(){
        $('#SalInvoiceActivationForm')[0].reset();
    }



//END SCRIPT
});