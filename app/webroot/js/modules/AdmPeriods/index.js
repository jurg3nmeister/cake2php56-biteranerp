/* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro | JS: AdmPeriods/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 19/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#AdmPeriodIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {
            var htmlSwitchOnOff = fnGenerateHtmlSwitchOnOff(aData[1], aData[2]);
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[2]);

            jQuery('td:eq(1)', nRow).html(htmlSwitchOnOff);
            jQuery('td:eq(2)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function(oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [2]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 19/08/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var buttonHtml = ' <button type="button" id="delete-' + id + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 19/08/2014 | Developer: reyro | Description: generates an Html Row Checkbox Swtich Control
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
    
//    Created: 19/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
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
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnLogicDelete(id, jQuery(this)); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 19/08/2014 | Developer: reyro | Description: set user account active true or false | Request:Ajax
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
    
//    Created: 19/08/2014 | Developer: reyro | Description: toogle Yes or No checkbox
    function fnToogleCheckbox(active, objectCheckbox) {
        if (active === 1) {
            objectCheckbox.prop('checked', false);
        } else {
            objectCheckbox.prop('checked', true);
        }
    }
    
//    Created: 19/08/2014 | Developer: reyro | Description: restores logic deleted users | Request:Ajax
    function fnLogicDelete(id, objectButton) {
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
