/* (c)Bittion | Created: 07/06/2015 | Developer:reyro | JS: SalProposals/update */
jQuery(document).ready(function () {
//START SCRIPT

    var SalProposalId = jQuery('#SalProposalId');

//************************************** EXEC MAIN - START **************************************
//    Created: 07/06/2015 | Developer: reyro | Description: Validates Update Form
    jQuery("#SalProposalSaveForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[SalProposal][price]': {
                required: true,
                number: true
            }
        },
        // Messages for form validation
        messages: {},
        // Do not change code below
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

    jQuery('#SalProposalSalCustomerId').select2();
    jQuery('#SalProposalName').focus();

//    Created: 07/06/2015 | Developer: reyro | Description: initiate datepicker
    jQuery('#SalProposalSentDate').datepicker({
        dateFormat: 'dd/mm/yy',
        prevText: '<i class="fa fa-chevron-left"></i>',
        nextText: '<i class="fa fa-chevron-right"></i>'
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 07/06/2015 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#SalProposalSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    if(SalProposalId.val() == ""){
                        window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']);
                    }
                    SalProposalId.val(response['data']['id']);
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