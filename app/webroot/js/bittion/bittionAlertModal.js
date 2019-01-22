/* (c)Bittion Admin Module | Created: 15/08/2014 | Developer:reyro | Description: alert modals to select option yes no before doing something */
////$(document).ready(function(){ // with this doesn't work
//START SCRIPT	
//***************************************************************************//
//************************START - BITTION ALERT MODAL************************//
//***************************************************************************//
//alert('cargo');
var html = '';
function showBittionAlertModal(arg) {
    if (arg === undefined) {
        //alert('No hay objeto definido');
        //return false;
        var arg = {
            title: 'Mensaje',
            content: '¿Esta seguro?',
            btnYes: 'Si',
            btnNo: 'No',
            btnOptional: ''
        };
    } else {
        if (arg.title === undefined) {
            arg.title = 'Mensaje';
        }
        if (arg.content === undefined) {
            arg.content = '¿Esta seguro?';
        }
        if (arg.btnYes === undefined) {
            arg.btnYes = 'Si';
        }
        if (arg.btnNo === undefined) {
            arg.btnNo = 'No';
        }
        if (arg.btnOptional === undefined) {
            arg.btnOptional = '';
        }
    }

    $('#content').append(createAlertModal(arg));
    if ($('#bittionBtnNo').length > 0) {
        $('#bittionBtnNo').bind("click", function () {
            hideBittionAlertModal();
        });
        
    }
    if ($('#bittionBtnYes').length > 0) {
        $('#bittionBtnYes').bind("click", function () {
            hideBittionAlertModal();
        });
    }
    $('#bittionAlertModal').modal({
        show: 'true',
        backdrop: 'static',
        keyboard: false //no escape key action, because will need an especial event that calls hideBittionAlertModal() and it's really not needed
    });
    
    if ($('#bittionBtnNo').length > 0) {
        jQuery('#bittionBtnNo').focus();
    }
}

function hideBittionAlertModal() {
    $('#bittionAlertModal').modal('hide');
    $('body').removeClass('modal-open');//Only For Twitter Bootstrap 3
    $('.modal-backdrop').remove();//Only For Twitter Bootstrap 3
    $('#bittionAlertModal').remove();
}


function createAlertModal(arg) {
    html = '<div class="modal show" id="bittionAlertModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += createHeader(arg.title);
    html += createBody(arg.content);
    html += createFooter(arg.btnYes, arg.btnNo, arg.btnOptional);
    html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}

function createHeader(title) {
    html = '<div class="modal-header">';
    //html += '<button data-dismiss="modal" class="close" type="button">×</button>'; //not using it 'cause will need to call hideBittionAlertModal()
    html += '<h3 class="modal-title" id="myModalLabel">' + title + '</h3>';
    html += '</div>';
    return html;
}
function createBody(content) {
    html = '<div class="modal-body">';
    html += '<h5>' + content + '</h5>';
    html += '</div>';
    return html;
}
function createFooter(btnYes, btnNo, btnOptional) {

    if (btnYes === '' && btnNo === '' && btnOptional === '') {
        return '';
    } else {
        html = '<div class="modal-footer">';
        if (btnNo !== '') {
            html += '<button type="button" id="bittionBtnNo" class="btn btn-default btn-lg" >' + btnNo + '</button>';
        }
        if (btnYes !== '') {
            html += '<button type="button" id="bittionBtnYes" class="btn btn-primary btn-lg" >' + btnYes + '</button>';
        }
        if (btnOptional !== '') {
            html += '<button type="button" id="bittionBtnOptional" class="btn btn-primary btn-lg">' + btnOptional + '</button>';
        }
        html += '</div>';
        return html;
    }

}
//***************************************************************************//
//************************FINISH - BITTION ALERT MODAL************************//
//***************************************************************************//


//END SCRIPT
//});
