/* (c)Bittion Admin Module | Created: 05/08/2014 | Developer:reyro | JS: AdmUsers/deleted_users */
jQuery(document).ready(function() {
//START SCRIPT

//************************************** EXEC MAIN - START **************************************

//    Created: 12/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery("#AdmUserDeletedUsersDT").DataTable({
        /////////Ajax
        "bServerSide": true
        , "sAjaxSource": bittionUrlProjectAndController + 'fnReadDeletedUsers'
        , "sServerMethod": "POST"
        , "bStateSave": true  //Saves the last search and/or pagination :)
        , "fnCreatedRow": function(nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[4]);
            jQuery('td:eq(4)', nRow).html(htmlTableButtons);
        }
        , "fnDrawCallback": function(oSettings) {
            fnBindEventDataTableControls();
        }
        , "aoColumnDefs": [
            {"bSortable": false, "aTargets": [4]} //disable active and buttons columns
        ]
        , "oLanguage": bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 12/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnRestore').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de restaurar el usuario eliminado?', title: 'Restaurar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnRestore(id, jQuery(this)); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 12/08/2014 | Developer: reyro | Description: restores logic deleted users | Request:Ajax
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
                }else{
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 12/08/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
        var buttonHtmlRestore = '<button type="button" id="restore-' + id + '" class="btn btn-success btnRestore" title="Restaurar"> \n\
                                Restaurar  </button>';
        return buttonHtmlRestore;
    }

//END SCRIPT
});

