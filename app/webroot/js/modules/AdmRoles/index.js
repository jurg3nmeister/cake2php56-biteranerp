/* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro | JS: AdmRoles/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 19/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#AdmRoleIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[2]);
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
        var buttonHtml = '<a href="' + bittionUrlProjectAndController + 'save/id:' + id + '" class="btn btn-primary" title="Editar"><i class="fa fa-pencil"></i></a>';
        buttonHtml += ' <button type="button" id="delete-' + id + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 19/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnDelete(id, jQuery(this)); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 19/08/2014 | Developer: reyro | Description: restores logic deleted users | Request:Ajax
    function fnDelete(id, objectButton) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDelete',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                    table1.draw();
                } else {
                    bittionShowGrowlMessage(response['status'], response['title'], response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }
//END SCRIPT
});
