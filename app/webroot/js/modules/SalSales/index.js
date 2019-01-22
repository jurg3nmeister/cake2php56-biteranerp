/* (c)Bittion | Created: 07/10/2014 | Developer:reyro | JS: SalSales/index */
jQuery(document).ready(function () {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 25/09/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#SalSaleIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function (nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[6]);
            jQuery('td:eq(6)', nRow).html(htmlTableButtons);
            fnPaintRow(jQuery(nRow), aData[6]['lc_state']);
        }
        , 'fnDrawCallback': function (oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [6]} //disable active and buttons columns
        ]
//        , 'aaSorting': [[0,'desc']] //initial column sorting, disable bStateSave
//        , 'order':[[0,'desc']] //default order doesn't work with bStateSave
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 13/10/2014 | Developer: reyro | Description: Paints a row according to its lc_state
    function fnPaintRow(row, lcState) {
        if(lcState === 'APPROVED'){
            row.addClass('success');
        }else if (lcState === 'CANCELED'){
            row.addClass('danger');
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(data) {
        var buttonHtml = '<a href="' + bittionUrlProjectAndController + 'save/id:' + data['id'] + '" class="btn btn-sm btn-primary" title="Editar"><i class="fa fa-pencil"></i> </a>';
        if (data['lc_state'] === 'ELABORATED') {
            buttonHtml += ' <button type="button" id="delete-' + data['id'] + '" class="btn btn-danger btn-sm btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        }
        return buttonHtml;
    }
    
//    Created: 25/09/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
    function fnBindEventDataTableControls() {
        jQuery('.btnDelete').on('click', function (event) {
            event.preventDefault();
            var arrayRowId = this.id.split('-');
            var id = arrayRowId[1];
            showBittionAlertModal({content: 'Se eliminará permanentemente. ¿Está seguro?', title: 'Eliminar'});
            jQuery('#bittionBtnYes').click(function (event) {
                fnDelete(id); //(id, object)
                event.preventDefault();
            });
        });
    }

//    Created: 25/09/2014 | Developer: reyro | Description: deletes | Request:Ajax
    function fnDelete(id) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnDelete',
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
