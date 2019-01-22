/* (c)Bittion Admin Module | Created: 05/08/2014 | Developer:reyro | JS: AdmUsers/index */
jQuery(document).ready(function() {
//START SCRIPT

//************************************** EXEC MAIN - START **************************************
    
//    Created: 10/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery("#AdmUserIndexDT").DataTable({
        /////////Ajax
        "bServerSide": true
        , "sAjaxSource": bittionUrlProjectAndController + 'fnRead'
        , "sServerMethod": "POST"
        , "bStateSave": true  //Saves the last search and/or pagination :)
        , "fnCreatedRow": function(nRow, aData, iDataIndex) {
            var htmlSwitchOnOff = fnGenerateHtmlSwitchOnOff(aData[1]['active'], aData[1]['id']);
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[1]['id']);
            jQuery('td:eq(1)', nRow).html(htmlSwitchOnOff);
            jQuery('td:eq(5)', nRow).html(htmlTableButtons);
        }
        , "fnDrawCallback": function(oSettings) {
            fnBindEventDataTableControls();
        }
        , "aoColumnDefs": [
            {"bSortable": false, "aTargets": [5]} //disable active and buttons columns
        ]
        , "oLanguage": bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 10/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.chkActive').on('click', function(event) {
//            event.preventDefault(); //not needed for checkbox
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            var active = 0;
            if (jQuery(this).is(':checked')) {
                active = 1;
            }
            fnActivate(id, active, jQuery(this));
        });
        jQuery('.btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar este usuario?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnLogicDelete(id, jQuery(this)); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 10/08/2014 | Developer: reyro | Description: logic deletes a user | Request:Ajax
    function fnLogicDelete(id, row) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnLogicDelete',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    table1.draw();
                }else{
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 10/08/2014 | Developer: reyro | Description: set user account active true or false | Request:Ajax
    function fnActivate(id, active, objectCheckbox) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnActivate',
            dataType: 'json',
            data: {
                id: id,
                active: active
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnToogleCheckbox(active, objectCheckbox);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                fnToogleCheckbox(active, objectCheckbox);
            }
        });
    }

//    Created: 10/08/2014 | Developer: reyro | Description: toogle Yes or No checkbox
    function fnToogleCheckbox(active, objectCheckbox) {
        if (active === 1) {
            objectCheckbox.prop('checked', false);
        } else {
            objectCheckbox.prop('checked', true);
        }
    }

//    Created: 10/08/2014 | Developer: reyro | Description: generates an Html Row Checkbox Swtich Control
    function fnGenerateHtmlSwitchOnOff(state, id) {
        var checked = '';
        if (state === true) {
            checked = 'checked="checked"';
        }
        var controlHtml = ' <span class="onoffswitch"> \n\
                            <input type="checkbox" class="onoffswitch-checkbox chkActive" id="autoopen-' + id + '"  ' + checked + '> \n\
                            <label class="onoffswitch-label" for="autoopen-' + id + '"> \n\
                                <span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span> \n\
                                <span class="onoffswitch-switch"></span> \n\
                            </label> \n\
                        </span>';
        return controlHtml;
    }

//    Created: 10/08/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {

        var buttonHtmlEdit = '<a href="' + bittionUrlProjectAndController + 'save/id:' + id + '" class="btn btn-primary" title="Editar"> \n\
                            <i class="fa fa-pencil"></i></a>';
        var buttonHtmlDelete = ' <a href="#" id="delete-' + id + '" class="btn btn-danger btnDelete" title="Eliminar"> \n\
                            <i class="fa fa-trash-o"></i></a>';
        return buttonHtmlEdit + buttonHtmlDelete;
    }

//END SCRIPT
});

