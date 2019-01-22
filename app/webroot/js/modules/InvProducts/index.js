/* (c)Bittion | Created: 18/09/2014 | Developer:reyro | JS: InvProducts/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 18/09/2014 | Developer: reyro | Description: initiate DataTable
    var table1 = jQuery('#InvProductIndexDT').DataTable({
        /////////Ajax
        'bServerSide': true
        , 'sAjaxSource': bittionUrlProjectAndController + 'fnRead'
        , 'sServerMethod': 'POST'
        , 'bStateSave': true  //Saves the last search and/or pagination :)
        , 'fnCreatedRow': function(nRow, aData, iDataIndex) {


            var img = '<img src="'+bittionUrlImg+'/products/'+aData[0]+'" width="80px" height="80px">';
            jQuery('td:eq(0)', nRow).html(img);

            var action = 'save';
            var recipe = '<span class="badge bg-color-blue">ENS</span>';
            if(aData[8] === true){
                jQuery('td:eq(1)', nRow).html(aData[1]+'<br>'+recipe);
                action = 'save_with_recipe';
            }

            var htmlTableButtons = fnGenerateHtmlTableButtons(aData[7], action);

            jQuery('td:eq(7)', nRow).html(htmlTableButtons);
            var active = '<span class="badge bg-color-red">NO</span>';
            if(aData[6] === true){
                active = '<span class="badge bg-color-greenLight">SI</span>';
            }
            jQuery('td:eq(6)', nRow).html(active);
        }
        , 'fnDrawCallback': function(oSettings) {
            fnBindEventDataTableControls();
        }
        , 'aoColumnDefs': [
            {'bSortable': false, 'aTargets': [7]}, //disable active and buttons columns
            {'bSortable': false, 'aTargets': [0]} //disable active and buttons columns
        ]
        , 'oLanguage': bittionDataTableLanguage //belongs to bittionMain
    });
//************************************** EXEC MAIN - END **************************************

//    Created: 18/09/2014 | Developer: reyro | Description: generates Html Row Buttons Edit and Delete
    function fnGenerateHtmlTableButtons(id, action) {

	var buttonHtml = '<a href="' + bittionUrlProjectAndController + action+'/id:' + id + '" class="btn btn-primary btn-sm" title="Editar"><i class="fa fa-pencil"></i></a>';
        buttonHtml += ' <button type="button" id="delete-' + id + '" class="btn btn-danger btn-sm btnDelete" title="Eliminar"><i class="fa fa-trash-o"></i></button>';
        return buttonHtml;
    }
	
//    Created: 18/09/2014 | Developer: reyro | Description: bind events to DataTable Html Controls
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

//    Created: 18/09/2014 | Developer: reyro | Description: deletes | Request:Ajax
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
