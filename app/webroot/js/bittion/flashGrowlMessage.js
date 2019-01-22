/* (c)Bittion Admin Module | Created: 15/08/2014 | Developer:reyro | Description: cakephp flash message shown in growl ajax mode */
$(document).ready(function() {
//START SCRIPT

//MAIN - START
    fnCreateFlashGrowlMessage();
//MAIN - END

//<div id = flashGrowlMessage> & flashMessage => is embedded in smartadmin.ctp

//    JS FUNCTION
//    Created: 05/Aug/2014 | Developer: reyro | Description: create a growl message on "Form Post" with session flash message of cakephp, mocking ajax.
//    Intructions: 
//    if post and if success or not use this in the cakephp code
//    For success: $this->Session->setFlash('', 'default', array('class'=>'flashGrowlSuccess'), 'flashGrowl');
//    For error:   this->Session->setFlash('', 'default', array('class'=>'flashGrowlError'), 'flashGrowl');
//    if setFlash('' ....=> is empty, will use default messages 

    function fnCreateFlashGrowlMessage() {
        if ($('#flashGrowlMessage').length > 0) {
            var color = '#C46A69';
            var title = 'Error!';
            var content = 'No se guardaron los datos, intente de nuevo.';
            var icon = 'fa-warning';
            if ($('.flashGrowlSuccess').length > 0) {
                color = '#739E73';
                title = 'Exito!';
                content = 'Datos guardados.';
                icon = 'fa-check';
            }
            if ($('#flashGrowlMessage').text() !== '') {
                content = $('#flashGrowlMessage').text();
            }
            $.smallBox({
                title: title,
                content: content,
                color: color,
                iconSmall: "fa " + icon + " bounce animated",
                timeout: 5000,
                sound:'no'
            });
        }
    }

//END SCRIPT
});