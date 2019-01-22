/* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro | JS: AdmPeriods/deleted_periods */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 19/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#AdmPeriodIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnReadDeletedPeriods'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[1]);
            jQuery('td:eq(1)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function(oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [1]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 19/08/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var buttonHtml = ' <button type="button" id="restore-' + id + '" class="btn btn-success btnRestore" title="Restaurar">Restaurar</button>';
        return buttonHtml;
    }
    
//    Created: 19/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnRestore').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de restaurar?', title: 'Restaurar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnRestore(id, jQuery(this)); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 19/08/2014 | Developer: reyro | Description: restores logic deleted users | Request:Ajax
    function fnRestore(id, objectButton) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnRestore',
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
