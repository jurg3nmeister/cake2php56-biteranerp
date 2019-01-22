/* (c)Bittion Admin Module | Created: 24/08/2014 | Developer:reyro | JS: AdmRolesMenus/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 24/08/2014 | Developer: reyro | Description: Validates Form
    jQuery('#AdmRolesMenuIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnCreate();
        },
        // Rules for form validation
        rules: {
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    jQuery('#AdmRolesMenuAdmRoleId').change(function() {
        fnRead(jQuery(this).val());
    });
//    fnRead(jQuery('#AdmRolesMenuAdmRoleId').val());
//************************************** EXEC MAIN - END **************************************

//    Created: 24/08/2014 | Developer: reyro | Description: get actions for a module and a role | Request: Ajax
    function fnRead(roleId) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnRead',
            dataType: 'json',
            data: {
                roleId: roleId
            },
            success: function(response) {
                var menu = fnGenerateHtmlMenu(response['menu']);
                jQuery('#treeMenu').html(menu);
                $('button').prop('disabled', false);
                if(response['empty'] > 0){
                    jQuery('#chkMain').prop('checked', true);
                }
                fnBindCheckboxEvents();
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 24/08/2014 | Developer: reyro | Description: create, update and delete roles menus | Request: Ajax
    function fnCreate() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnCreate',
            beforeSend: function() {
                jQuery('#btnSave').button('loading');
            },
            dataType: 'json',
            data: {
                data: jQuery('#AdmRolesMenuIndexForm').bittionSerializeObjectJson()
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#btnSave').button('reset');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#btnSave').button('reset');
            }
        });
    }

    function fnBindCheckboxEvents() {
        $('input[type=checkbox]').click(function() {
            var checked = false;
            if ($(this).is(':checked')) {
                checked = true;
            }
            $(this).closest("li").children("ul").children("li").find('input[type=checkbox]').prop('checked', checked); //check all children
            $(this).parents('ul').closest('li').find('input[type=checkbox]:first').prop('checked', true); //check first checkbox on every parent 
            //.css("background-color", "red")
        });
    }

//    Created: 24/08/2014 | Developer: reyro | Description: generates menu
    function fnGenerateHtmlMenu(data) {
        var html = '<ul>';
        html += '<li>';
//        html += '<span><i class="fa fa-sitemap"></i></span>';
        html += '<span><label class="checkbox inline-block"><input id="chkMain" type="checkbox" ><i></i>/</label></span>';
        html += '<ul>';
        html += fnGenerateLevel1(data);
        html += '</ul>';
        html += '</li>';
        html += '</ul>';
        return html;
    }

//    Created: 24/08/2014 | Developer: reyro | Description: generates first level
    function fnGenerateLevel1(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<span>' + '<label class = "checkbox inline-block">' + fnGenerateHtmlCheckbox(value['menu']['checked'], value['menu']['id']) + '<i></i>' + value['menu']['name'] + '</label>' + '</span>';
            html += '<ul>';
            html += fnGenerateLevel2(value['children']);
            html += '</ul>';
            html += '</li>';
        });
        return html;
    }

//    Created: 24/08/2014 | Developer: reyro | Description: generates second level    
    function fnGenerateLevel2(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<span>' + '<label class = "checkbox inline-block">' + fnGenerateHtmlCheckbox(value['menu']['checked'], value['menu']['id']) + '<i></i>' + value['menu']['name'] + '</label>' + '</span>';
            html += '<ul>';
            html += fnGenerateLevel3(value['children']);
            html += '</ul>';
            html += '</li>';
        });
        return html;
//                < span > < label class = "checkbox inline-block" >< input type = "checkbox" checked = "checked" name = "checkbox-inline" >< i > < /i>Sunny.Ahmed</label > < /span>
    }

//    Created: 24/08/2014 | Developer: reyro | Description: generates third level    
    function fnGenerateLevel3(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<span>' + '<label class = "checkbox inline-block">' + fnGenerateHtmlCheckbox(value['menu']['checked'], value['menu']['id']) + '<i></i>' + value['menu']['name'] + '</label>' + '</span>';
            html += '</li>';
        });
        return html;
    }

//    Created: 24/08/2014 | Developer: reyro | Description: generates html checkbox    
    function fnGenerateHtmlCheckbox(checked, menuId) {
        var propertyChecked = '';
        if (checked === true) {
            propertyChecked = 'checked="checked"';
        }
        return '<input value="' + menuId + '" name="menusIds[]" type="checkbox" ' + propertyChecked + '>';
    }
//END SCRIPT
});