/* (c)Bittion | Created: 15/01/2015 | Developer:reyro | JS: SalOffices/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 15/01/2015 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#SalOfficeIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[4]);
            jQuery('td:eq(4)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function(oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [4]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 15/01/2015 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
	var buttonHtml = '<a href="' + bittionUrlProjectAndController + 'save/id:' + id + '" class="btn btn-primary btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>';
        buttonHtml += ' <button type="button" id="delete-' + id + '" class="btn btn-danger btn-sm btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }
	
//    Created: 15/01/2015 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function(event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function(event) {
                fnDelete(id); //(id, object)
                event.preventDefault();
            });
        });
    }	

//    Created: 15/01/2015 | Developer: reyro | Description: deletes | Request:Ajax
    function fnDelete(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDelete',
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
//END SCRIPT
});
