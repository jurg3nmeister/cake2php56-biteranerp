/* (c)Bittion | Created: 18/09/2014 | Developer:reyro | JS: InvProducts/save */
jQuery(document).ready(function () {
//START SCRIPT

    var InvProductId = jQuery('#InvProductId');
    var InvProductId2 = jQuery('#InvProductId2');

//************************************** EXEC MAIN - START **************************************
//    Created: 18/09/2014 | Developer: reyro | Description: Validates Update Form
    jQuery("#InvProductSaveWithRecipeForm").validate({
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
    jQuery("#InvModalProductPriceSaveWithRecipeForm").submit(function(event){
        jQuery('#modalPriceProduct').modal('hide');
        jQuery("#InvPrice0Price").val(jQuery("#InvModalProductPriceSalePrice").val());
        jQuery("#InvProductPurchasePrice").val(jQuery("#InvModalProductPricePurchasePrice").val());
        jQuery("#InvProductProfitPercentage").val(jQuery("#InvModalProductPriceProfitPercentage").val());
        event.preventDefault();
    });

//    Created: 06/06/2015 | Developer: reyro | Description: On click modal create product
    jQuery("#btnModalInvRecipe").click(function(event) {
        if(InvProductId.val() == ''){
            alert('Primero debe guardar el producto');
        }else{
            jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});
            fnReadProducts(jQuery('#InvProductId').val());
        }
        event.preventDefault();
    });


    //jQuery('#InvRecipeInvProductId').select2();


//    Created: 06/06/2015 | Developer: reyro | Description: focus on select product when modal show  | Obs: must be outside any event/function otherwise it will fire many times
    jQuery('#modalProduct').on('shown.bs.modal', function () {
        if (jQuery('#InvRecipeId').val() === '') {//CREATE
            jQuery('#InvRecipeInvProductId').select2('focus');
        } else {//UPDATE
            jQuery('#InvRecipeSalePrice').focus();
        }
        //alert('jolldfd');
    });

//    Created: 06/06/2015 | Developer: reyro | Description: Always reset form when modal hides | Obs: must be outside any event/function otherwise it will fire many times
    $('#modalProduct').on('hidden.bs.modal', function () {
        //ValidateSaleDetail.resetForm();
        jQuery('label').removeClass('state-success state-error');
        jQuery('#InvRecipeId').val('');
        jQuery('#InvRecipeInvProductId').val('');
        jQuery('#InvRecipeQuantity').val('');
        jQuery('#modalRecipePurchasePrice').text('');
        jQuery('#modalRecipeSalePrice').text('');
        jQuery('#modalUpdateProductName').text('');
        jQuery('#btnModalInvRecipe').focus();
    });

//    Created: 07/10/2014 | Developer: reyro | Description: event change customers
    jQuery('#InvRecipeInvProductId').change(function () {
        fnReadModalAddProductPrices($(this).val());
    });

//    Created: 07/06/2015 | Developer: reyro | Description: read products details | Request: Ajax
    var table1 = jQuery('#InvRecipeIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnReadIngredients'
        , 'sServerMethod': 'POST'
        , 'bPaginate': false
        , 'sDom': 't'
        , "fnServerParams": function (aoData) {
            aoData.push(
                {name: "id", value: InvProductId.val()}
            );
        }
        , 'fnCreatedRow': function (nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[7]);
            jQuery('td:eq(7)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function (oSettings) {
            fnBindEventDataTableControls();
            var id = InvProductId.val();
            if (id !== '') {
                fnReadTotal(id);
            }
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [0, 6]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 07/10/2014 | Developer: reyro | Description: Validates Save Offer
    var ValidateIngredient = jQuery("#InvRecipeSaveWithRecipeForm").validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function (form) {
            //Replace form submit for:
            //fnSaveSaleAndDetail();
            //alert('save');
            fnSaveIngredient();
        },
        // Rules for form validation
        rules: {
            'data[InvRecipe][inv_product_id]': {
                required: true
            },
            //'data[InvRecipe][sale_price]': {
            //    required: true,
            //    number: true,
            //    positiveValue: true,
            //    min: 0.01
            //},
            'data[InvRecipe][quantity]': {
                required: true,
                digits: true,
                min: 1
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

//    Created: 07/06/2015 | Developer: reyro | Description: fills modal prices | Request:Ajax
    function fnReadModalAddProductPrices(productId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadModalAddProductPrices',
            dataType: 'json',
            data: {
                productId: productId
            },
            success: function (response) {
                jQuery('#modalRecipePurchasePrice').text(response['purchasePrice']);
                jQuery('#modalRecipeSalePrice').text(response['salePrice']);
                //jQuery('#modalRecipeProfitPercentage').text(response['profitPercentage']);
                //jQuery('#modalRecipeProfit').text(response['profit']);
                //jQuery('#InvRecipeSalePrice').val(response['salePrice']);
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 06/06/2015 | Developer: reyro | Description: read products | Request:Ajax
    function fnReadProducts(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadProducts',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                fillSelectProducts(response['products'], 'InvRecipeInvProductId');
                //jQuery('#InvRecipeInvProductId').show();
                jQuery('#sectionUpdateAddProduct').show();
                jQuery('#modalLabelUpdateProductName').hide();
                //jQuery('#InvRecipeInvProductId').closest('section').show();
                //jQuery('#sectionUpdateInvRecipe').hide();
            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 06/06/2015 | Developer: reyro | Description: fill html select
    function fillSelectProducts(data, id) {
        var select = jQuery('#' + id);
        select.empty();
        select.append('<option value="" disabled="disabled" selected="selected">Elija un producto </option>');

        $.each(data, function (index, value) {
            select.append('<option value="'
            + index
            + '">'
            + value
            + '</option>');
        });
        select.select2();
    }

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
                data: jQuery('#InvProductSaveWithRecipeForm').bittionSerializeObjectJson()
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

//    Created: 07/06/2015 | Developer: reyro | Request:Ajax
    function fnSaveIngredient() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSaveIngredient',
            async:false,
            dataType: 'json',
            data: {
                InvRecipeId: jQuery('#InvRecipeId').val(),
                quantity: jQuery('#InvRecipeQuantity').val(),
                productId: InvProductId.val(),
                productIngredientId: jQuery('#InvRecipeInvProductId').val()
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    table1.draw();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
                jQuery('#modalProduct').modal('hide');
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

//    Created: 07/06/2015 | Developer: reyro | Description: sum columns | Request:Ajax
    function fnReadTotal(){
        var sumPurchase = 0;
        var sumSale = 0;
        var sumQuantity = 0;
        var sumTotal = 0;

        if ($('#InvRecipeIndexDT > tbody  > tr:first').find('td').eq(0).text() != ""){
            $('#InvRecipeIndexDT > tbody  > tr').each(function() {
                sumPurchase = sumPurchase + parseFloat($(this).find('td').eq(3).text());
                sumSale = sumSale + parseFloat($(this).find('td').eq(4).text());
                sumQuantity = sumQuantity + parseInt($(this).find('td').eq(5).text());
                sumTotal = sumTotal + parseFloat($(this).find('td').eq(6).text());
            });
        }
        jQuery('#spanPurchase').text(sumPurchase.toFixed(2));
        jQuery('#spanSale').text(sumSale.toFixed(2));
        jQuery('#spanQuantity').text(sumQuantity);
        jQuery('#spanTotal').text(sumTotal.toFixed(2));

        //if(jQuery('#InvPrice0Price').val() == 0){
        //    jQuery('#InvPrice0Price').val(sumTotal.toFixed(2));
        //}

    }

//    Created: 07/06/2015 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var disabled = '';
        var buttonHtml = '<button type="button" ' + disabled + ' id="update-' + id + '" class="btn btn-primary btn-xs btnUpdate" title="Editar"><i class="fa fa-pencil"></i></button>';
        buttonHtml += ' <button type="button" ' + disabled + ' id="delete-' + id + '" class="btn btn-danger btn-xs btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 07/06/2015 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function (event) {
                fnDeleteIngredient(id); //(id, object)
                event.preventDefault();
            });
        });

        jQuery('.btnUpdate').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            fnReadIngredientUpdate(id);
            jQuery('#modalProduct').modal({show: 'true', backdrop: 'static'});
        });
    }

//    Created: 07/06/2014 | Developer: reyro | Description: fills total | Request:Ajax
    function fnReadIngredientUpdate(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadIngredientUpdate',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                jQuery('#InvRecipeId').val(id);
                //var selectProduct = jQuery('#SalSalesDetailInvProductId');
                var selectProduct = jQuery('#InvRecipeInvProductId');
                //selectProduct.closest('section').hide();
                selectProduct.empty();
                selectProduct.append('<option value="' + response['ingredientId'] + '" selected="selected"></option>');

                jQuery('#sectionUpdateAddProduct').hide();
                jQuery('#modalUpdateProductName').show().text(response['productName']);
                jQuery('#modalLabelUpdateProductName').show();

                jQuery('#modalRecipePurchasePrice').text(response['purchasePrice']);
                jQuery('#modalRecipeSalePrice').text(response['salePrice']);
                jQuery('#InvRecipeQuantity').val(response['quantity']);

            },
            error: function (response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 07/10/2014 | Developer: reyro | Description: deletes sales detail | Request:Ajax
    function fnDeleteIngredient(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDeleteIngredient',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    table1.draw();
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