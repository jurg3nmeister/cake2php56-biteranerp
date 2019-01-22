/* (c)Bittion | Created: 18/09/2014 | Developer:reyro | JS: InvProducts/save */
jQuery(document).ready(function () {
//START SCRIPT

    var InvProductId = jQuery('#InvProductId');
    var InvProductId2 = jQuery('#InvProductId2');

//************************************** EXEC MAIN - START **************************************
//    Created: 18/09/2014 | Developer: reyro | Description: Validates Update Form
    jQuery("#InvProductSaveForm").validate({
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

    jQuery('#InvProductInvCategoryId').focus();
    
    jQuery('#InvProductCode').keyup(function(e){
       this.value = this.value.toUpperCase();
    });
//    jQuery('#InvProductMeasure').keyup(function(e){
//       this.value = this.value.toUpperCase();
//    });

//    Created: 03/05/2015 | Developer: reyro | Description: Calls Modal Product Price
//    jQuery("#linkGenerateProductPrice").click(function () {
//        jQuery('#modalPriceProduct').modal({show: 'true', backdrop: 'static'});
//    });

//    Created: 03/05/2015 | Developer: reyro | Description: Calls Modal Product Price
    jQuery("#InvPrice0Price").focus(function(event) {
        jQuery('#modalPriceProduct').modal({show: 'true', backdrop: 'static'});
        if(InvProductId.val() != ""){  //only when update
            fnReadProductPrices(InvProductId.val());
            jQuery("#InvModalProductPricePurchasePrice").val(jQuery("#InvProductPurchasePrice").val());
            jQuery("#InvModalProductPriceProfitPercentage").val(jQuery("#InvProductProfitPercentage").val());
            generateSalePrice(jQuery("#InvModalProductPricePurchasePrice").val(), jQuery("#InvModalProductPriceProfitPercentage").val());
        }
    });

//    Created: 03/05/2015 | Developer: reyro | Description: On event modal shown
    jQuery('#modalPriceProduct').on('shown.bs.modal', function () {
        jQuery("#InvModalProductPricePurchasePrice").focus();
    });

//    Created: 03/05/2015 | Developer: reyro | Description: Modal: generate sale price
    jQuery("#InvModalProductPricePurchasePrice").keydown(function(event) {
        // Allow only backspace and delete and period
        if (event.keyCode === 8 || event.keyCode === 9 || event.keyCode == 190) {
            // let it happen, don't do anything
        } else {
            // Ensure that it is a number and stop the keypress
            if ((event.keyCode < 96 || event.keyCode > 105)) { //enables numeric keypad
                if ((event.keyCode < 48 || event.keyCode > 57)) {
                    event.preventDefault();
                }
            }
        }
    });

//    Created: 03/05/2015 | Developer: reyro | Description: Modal: generate sale price
    jQuery("#InvModalProductPriceProfitPercentage").keydown(function(event) {
        // Allow only backspace and delete
        if (event.keyCode === 8 || event.keyCode === 9) {
            // let it happen, don't do anything
        }else if(event.keyCode === 13) {
            if(jQuery("#InvModalProductPricePurchasePrice").val() == ""){
                jQuery("#InvModalProductPricePurchasePrice").focus();
            }else{
                var salePrice = generateSalePrice(jQuery("#InvModalProductPricePurchasePrice").val(), jQuery("#InvModalProductPriceProfitPercentage").val());
                jQuery("#InvModalProductPriceSalePrice").val(salePrice.toFixed(2));
            }
            event.preventDefault();

        }else {
            // Ensure that it is a number and stop the keypress
            if ((event.keyCode < 96 || event.keyCode > 105)) { //enables numeric keypad
                if ((event.keyCode < 48 || event.keyCode > 57)) {
                    event.preventDefault();
                }
            }
        }
    });

//    Created: 03/05/2015 | Developer: reyro | Description: Modal: generate sale price
    jQuery("#InvModalProductPriceSalePrice").keydown(function(event) {
        // Allow only backspace and delete and period and enter
        if (event.keyCode === 8 || event.keyCode === 9 || event.keyCode == 190 || event.keyCode == 13) {
            // let it happen, don't do anything
        } else {
            // Ensure that it is a number and stop the keypress
            if ((event.keyCode < 96 || event.keyCode > 105)) { //enables numeric keypad
                if ((event.keyCode < 48 || event.keyCode > 57)) {
                    event.preventDefault();
                }
            }
        }
    });

//    Created: 03/05/2015 | Developer: reyro | Description: Modal Form on submit event
    jQuery("#InvModalProductPriceSaveForm").submit(function(event){
        jQuery('#modalPriceProduct').modal('hide');
        jQuery("#InvPrice0Price").val(jQuery("#InvModalProductPriceSalePrice").val());
        jQuery("#InvProductPurchasePrice").val(jQuery("#InvModalProductPricePurchasePrice").val());
        jQuery("#InvProductProfitPercentage").val(jQuery("#InvModalProductPriceProfitPercentage").val());
        event.preventDefault();
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 03/05/2015 | Developer: reyro | Generates product sale price
    function generateSalePrice(purchasePrice, profitPercentage){
        //purchasePrice = parseFloat(purchasePrice).toFixed(2);
        //profitPercentage = parseFloat(profitPercentage).toFixed(2);

        var profit = (purchasePrice * profitPercentage)/100;
        var purchaseProfit = profit + parseFloat(purchasePrice);
        var iva = (purchaseProfit*13) / 100;
        var it = (purchaseProfit*3) / 100;
        var salePrice = purchaseProfit + iva + it;
       jQuery("#modalProfit").text(profit);
       jQuery("#modalPurchaseProfit").text(purchaseProfit);
       jQuery("#modalIva").text(iva.toFixed(2));
       jQuery("#modalIt").text(it.toFixed(2));
       jQuery("#modalSalePrice").text(salePrice.toFixed(2));
       return salePrice;
    }

//    Created: 18/09/2014 | Developer: reyro | Request:Ajax 
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
            dataType: 'json',
            data: {
                data: jQuery('#InvProductSaveForm').bittionSerializeObjectJson()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    if(InvProductId.val() == ""){
                        window.history.pushState('obj', 'newtitle', bittionUrlAction+'/id:'+response['data']['id']);
                    }
                    InvProductId.val(response['data']['id']);
                    InvProductId2.val(response['data']['id']);
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

//    Created: 03/05/2015 | Developer: reyro | Description: fills salePrice and price | Request:Ajax
    function fnReadProductPrices(productId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProductPrices',
            dataType: 'json',
            data: {
                productId: productId
            },
            success: function (response) {
                jQuery('#InvModalProductPriceSalePrice').val(response['lastPrice']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//END SCRIPT
});