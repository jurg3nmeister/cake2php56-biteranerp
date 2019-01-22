/* (c)Bittion Admin Module | Created: 15/08/2014 | Developer:reyro | Description: global javascript functions*/
//$(document).ready(function() { //With this uncommented doesn't work globally
//START SCRIPT

//Check url's action and controller
var bittionUrlHost = window.location.host;
var bittionUrlPathName = window.location.pathname;
var bittionUrlPaths = bittionUrlPathName.split('/');

var bittionUrlProjectAndController = '';
var bittionUrlAction = '';
var bittionUrlActionValue1 = '';

var bittionUrlController = '';
var bittionUrlImg = '';
if (bittionUrlHost.toUpperCase() === 'LOCALHOST' || bittionUrlHost === '127.0.0.1') { //check if local or remote
    bittionUrlController = bittionUrlPaths[2];
    bittionUrlImg = '/' + bittionUrlPaths[1] + '/img/';
    bittionUrlSound = '/' + bittionUrlPaths[1] + '/sound/';

    bittionUrlProjectAndController = '/' + bittionUrlPaths[1] + '/' + bittionUrlPaths[2] + '/';
    bittionUrlAction = bittionUrlPaths[3];
    bittionUrlActionValue1 = bittionUrlPaths[4];
} else {
    bittionUrlController = bittionUrlPaths[1];
    bittionUrlImg = '/img/';
    bittionUrlSound = '/sound/';

    bittionUrlProjectAndController = '/' + bittionUrlPaths[1] + '/';
    bittionUrlAction = bittionUrlPaths[2];
    bittionUrlActionValue1 = bittionUrlPaths[3];
}
bittionSetActiveClassMenuAndRibbonTitle();
//***********************START - Execute MAIN*****************************//

//***********************END - Execute MAIN*****************************//

//    Created: 01/09/2014 | Developer: reyro | Description: set class active on <li> on tree menu and creates ribbon title (only supported for 2 levels for now)
function bittionSetActiveClassMenuAndRibbonTitle() {
    var currentMenuLevel1 = '';
    var currentMenuLevel2 = '';
    if (bittionUrlAction === undefined) {
        bittionUrlAction = 'index';
    }
    var menuId = '#menu-' + bittionUrlController + '-' + bittionUrlAction;
    if (jQuery(menuId).length > 0) {
        jQuery(menuId).addClass('active');
        //Ribbon title
        currentMenuLevel1 = jQuery(menuId).parent('ul').parent('li').find('.menu-item-parent').text();
        currentMenuLevel2 = jQuery(menuId + ' a').text();
        jQuery('#pageRibbon').html('<li>' + currentMenuLevel1 + '</li><li>' + currentMenuLevel2 + '</li>');
    }
}

//    Created: 10/08/2014 | Developer: reyro | Description: DataTable global language setup | Obs: Spanish
var bittionDataTableLanguage = {
    "sProcessing": "Procesando...",
//            "sLengthMenu": "Mostrar _MENU_ registros",
//    "sZeroRecords": "No se encontraron resultados",
    "sZeroRecords": "",
    "sEmptyTable": "No se encontraron resultados",
    "sInfo": "Mostrando del _START_ al _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando del 0 al 0 de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
//            "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

//    Created: 15/08/2014 | Developer: reyro | Description: For showing growl standard messages | Obs: Will be easy when changing language
function bittionShowGrowlMessage(status, title, content) {
//    var contents = {create: 'Creado', update: 'Cambios guardados', delete: 'Eliminado', active: 'Actividad cambiada', state: 'Estado cambiado', restore: 'Restaurado',
//        passwordChanged: 'Contraseña cambiada', ajaxError: 'Error interno del servidor', sessionExpired: 'Su sesión ha expirado, vuelva a iniciar sesión.'};
//    if (contents[content] !== undefined) {
//        content = contents[content];
//    }
    switch (status) {
        case 'SUCCESS':
            jQuery.smallBox({title: title, content: content, color: '#739E73', iconSmall: 'fa fa-check bounce animated', timeout: 5000, sound:'no'});
            break;
        case 'ERROR':
            jQuery.smallBox({title: title, content: content, color: '#C46A69', iconSmall: 'fa fa-times bounce animated', timeout: 5000, sound:'no'});
            break;
        case 'INFO':
            jQuery.smallBox({title: title, content: content, color: '#3276B1', iconSmall: 'fa fa-info bounce animated', timeout: 5000, sound:'no'});
            break;
        case 'WARNING':
            jQuery.smallBox({title: title, content: content, color: '#C79121', iconSmall: 'fa fa-warning bounce animated', timeout: 5000, sound:'no'});
            break;
    }
}

function bittionAjaxErrorHandler(response, status, error) {
    if (response.status === 403) {//session expired
        bittionShowGrowlMessage('WARNING', 'Alerta!', 'Su sesión ha expirado, vuelva a iniciar sesión.');
        setTimeout(function () {
            location.reload(); //Page refresh
        }, 3000);
    } else {
        bittionShowGrowlMessage('error', 'Error!', 'Error interno del servidor'); //production mode
//        bittionShowGrowlMessage('ERROR', 'Error!', 'Un error ocurrio: ' + status + ' - nError: ' + error); //debugging mode
    }
}

//    Created: 09/08/2014 | Developer: reyro | Description: serialize a form in Json Format
jQuery.fn.bittionSerializeObjectJson = function (options) {

    options = jQuery.extend({}, options);

    var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push": /^$/,
                "fixed": /^\d+$/,
                "named": /^[a-zA-Z0-9_]+$/
            };


    this.build = function (base, key, value) {
        base[key] = value;
        return base;
    };

    this.push_counter = function (key) {
        if (push_counters[key] === undefined) {
            push_counters[key] = 0;
        }
        return push_counters[key]++;
    };

    jQuery.each(jQuery(this).serializeArray(), function () {

        // skip invalid keys
        if (!patterns.validate.test(this.name)) {
            return;
        }

        var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

        while ((k = keys.pop()) !== undefined) {

            // adjust reverse_key
            reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

            // push
            if (k.match(patterns.push)) {
                merge = self.build([], self.push_counter(reverse_key), merge);
            }

            // fixed
            else if (k.match(patterns.fixed)) {
                merge = self.build([], k, merge);
            }

            // named
            else if (k.match(patterns.named)) {
                merge = self.build({}, k, merge);
            }
        }

        json = jQuery.extend(true, json, merge);
    });

    return json;
};

$(window).load(function () { //before everythinh loads removes the loading page message
    $("#loading-page").remove();
});

//END SCRIPT	
//});

