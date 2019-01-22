/* (c)Bittion Admin Module | Created: 18/08/2014 | Developer:reyro | JS: AdmActions/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 18/08/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#AdmControllerIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {
            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[3]);
            jQuery('td:eq(2)', nRow).html('<a href="#" id="description-' + aData[3] + '"  class="AdmActionDescription" data-pk="' + aData[3] + '">' + aData[2] + '</a>');
            jQuery('td:eq(3)', nRow).html(htmlTableButtons);
        }
        , 'fnDrawCallback': function(oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [3]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });


//************************************** EXEC MAIN - END **************************************



//    Created: 18/08/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id) {
//        var buttonHtml = '<a href="' + bittionUrlProjectAndController + 'update/id:' + id + '" class="btn btn-primary" title="Editar"><i class="fa fa-pencil"></i></a>';
        var buttonHtml = ' <button type="button" id="delete-' + id + '" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }

//    Created: 18/08/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
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

        /////////////////////////////
//      x-editable -> field description
        fnUpdateXEditable();
    }

//    Created: 18/08/2014 | Developer: reyro | Description: deletes | Request:Ajax
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

    function fnUpdateXEditable() {
        jQuery('.AdmActionDescription').editable({
            type: 'text',
//            pk: 7,  //it is inside de <a> tag
            url: bittionUrlProjectAndController + 'fnUpdateXEditable',
            title: 'Descripción',
            validate: function(value) {
                if (jQuery.trim(value) === '')
                    return 'No puede estar vacio';
            },
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            success: function(response, newValue) {
//                userModel.set('username', newValue); //update backbone model
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    table1.draw(); //Using this in case there is a search, so must refresh datatable
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            }
        });
    }
//END SCRIPT
});
