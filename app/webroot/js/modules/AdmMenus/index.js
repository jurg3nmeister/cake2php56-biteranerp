/* (c)Bittion Admin Module | Created: 22/08/2014 | Developer:reyro | JS: AdmMenus/index */
jQuery(document).ready(function() {
//START SCRIPT
//************************************** EXEC MAIN - START **************************************
//    Created: 22/08/2014 | Developer: reyro | Description: Validates Form
    jQuery('#AdmMenuIndexForm').validate({
        onkeyup: false, //avoid requesting ajax every time keyup, only activates on blur and on submit
        submitHandler: function(form) {
            //Replace form submit for:
            fnSave();
        },
        // Rules for form validation
        rules: {
            'data[AdmMenu][order_menu]': {
                digits: true
            }
        },
        // Messages for form validation
        messages: {
        },
        // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }
    });

    fnRead();
//************************************** EXEC MAIN - END **************************************

//    Created: 22/08/2014 | Developer: reyro | Description: get actions for a module and a role | Request: Ajax
    function fnRead() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnRead',
//            async: false,
            dataType: 'json',
            data: {
            },
            success: function(response) {
                var menu = fnGenerateHtmlMenu(response);
                jQuery('#treeMenu').html(menu);
//                fnTreeViewAnimation();
                fnMenuEvents();
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: creates and updates according to the case | Request: Ajax
    function fnSave() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnSave',
//            async: false,
            dataType: 'json',
            data: {
                data: jQuery('#AdmMenuIndexForm').bittionSerializeObjectJson()
            },
            success: function(response) {
                if (response['status'] === 'SUCCESS') {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                    fnRead();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
                jQuery('#modalSave').modal('hide');
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
                jQuery('#modalSave').modal('hide');
            }
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: generates menu popover events
    function fnMenuEvents() {
        $('.menu-buttons').popover({
            trigger: 'manual',
            animation: 'true',
            placement: 'right',
            toggle: "popover",
//            content: "<button class='btn btn-success btnCreate' title='Crear hijo'><i class='fa fa-plus'></i></button> <button class='btn btn-primary btnUpdate' title='Editar'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btnDelete' title='Eliminar'><i class='fa fa-trash-o'></i></button>",
            container: 'body',
            html: 'true'
        });
        $('.menu-buttons').click(function(event) {
//            $(this).css( "background-color", "red" );
            var children = $(this).closest("li").children("ul").children("li").length;

            event.preventDefault();
            $('.menu-buttons').not(this).popover('hide');
            $(this).popover('show');

            //create button event
            $('.btnCreate').click(function(e) {
                var id = this.id;
                var idValues = id.split('-'); //name id 
                fnBtnCreateAction(idValues[1]);
//                alert(idValues[1]);
            });
            //update button event
            $('.btnUpdate').click(function(e) {
                var id = this.id;
                var idValues = id.split('-'); //name level id
                fnBtnUpdateAction(idValues[2], idValues[1]);
            });
            //delete button event
            $('.btnDelete').click(function(e) {
                if (children > 0) {
                    alert('Tiene hijos, no se puede eliminar!');
                } else {
                    var id = this.id;
                    var idValues = id.split('-'); //name level id
                    fnBtnDeleteAction(idValues[2]);
                }
            });
        });
        $('body').on('click', function(e) {
            $('.menu-buttons').each(function() {
                // hide any open popovers when the anywhere else in the body is clicked
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: create event action
    function fnBtnCreateAction(parentId) {
        jQuery('#AdmMenuIndexForm').get(0).reset();
        jQuery('#AdmMenuId').val('');
        var selectIcon = jQuery('#AdmMenuIcon');
        selectIcon.select2({formatResult: fnFormatSelect2WithIcons, formatSelection: fnFormatSelect2WithIcons});
        selectIcon.find('option').remove();
        selectIcon.append('<option value="">Ninguno</option>');
        fnFillHtmlSelect(selectIcon, fontAwesomeIcons);

        var selectParent = jQuery('#AdmMenuParent');
        selectParent.find('option').remove();
        selectParent.append('<option value="' + parentId + '">' + parentId + '</option>');
        jQuery('#selectParent').hide();
//        alert(parentId);
        fnReadActions();
        jQuery('#AdmMenuAdmActionId').select2();
        jQuery('#modalSave').modal({show: 'true', backdrop: 'static'});
        $('#modalSave').on('shown.bs.modal', function() {
            jQuery('#AdmMenuName').focus();
        });

    }

//    Created: 23/08/2014 | Developer: reyro | Description: update event action
    function fnBtnUpdateAction(id, level) {
        jQuery('#AdmMenuIndexForm').get(0).reset();
        var selectIcon = jQuery('#AdmMenuIcon');
        selectIcon.find('option').remove();
        selectIcon.append('<option value="">Ninguno</option>');
        fnFillHtmlSelect(selectIcon, fontAwesomeIcons);
        fnReadUpdate(id, level);
    }

//    Created: 23/08/2014 | Developer: reyro | Description: delete event action
    function fnBtnDeleteAction(id) {
        showBittionAlertModal({content: '¿Está seguro de eliminar?', title: 'Eliminar'});
        jQuery('#bittionBtnYes').click(function(event) {
            fnDelete(id);
            event.preventDefault();
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 
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
                    fnRead();
                } else {
                    bittionShowGrowlMessage(response['status'],response['title'],response['content']);
                }
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: list all actions | Request: Ajax
    function fnReadActions() {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadActions',
            dataType: 'json',
            success: function(response) {
                var selectActions = jQuery('#AdmMenuAdmActionId');
                selectActions.find('option').remove();
                selectActions.append('<option value="">Ninguna</option>');
                fnFillHtmlSelect(selectActions, response);
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: list data for update a menu (menu, actions, parents) | Request: Ajax
    function fnReadUpdate(id, level) {
        jQuery.ajax({
            type: 'POST',
            url: bittionUrlProjectAndController + 'fnReadUpdate',
            dataType: 'json',
            data: {id: id, level: level},
            success: function(response) {
                var selectActions = jQuery('#AdmMenuAdmActionId');
                selectActions.find('option').remove();
                selectActions.append('<option value="">Ninguna</option>');
                fnFillHtmlSelect(selectActions, response['actions']);

                var selectParent = jQuery('#AdmMenuParent');
                selectParent.find('option').remove();
                selectParent.append('<option value="">Ninguno</option>');
//                alert(response['menus'].length);
                if(response['menus']['menus'] !== undefined){
                    fnFillHtmlSelectObject(selectParent, response['menus']['menus']);
                }
                

                jQuery('#AdmMenuId').val(response['update']['AdmMenu']['id']);
                jQuery('#AdmMenuName').val(response['update']['AdmMenu']['name']);
                jQuery('#AdmMenuOrderMenu').val(response['update']['AdmMenu']['order_menu']);
                jQuery('#AdmMenuIcon').val(response['update']['AdmMenu']['icon']);
                jQuery('#AdmMenuAdmActionId').val(response['update']['AdmMenu']['adm_action_id']);
                jQuery('#AdmMenuParent').val(response['update']['AdmMenu']['parent']);

                jQuery('#selectParent').show();
                jQuery('#AdmMenuAdmActionId').select2();
                jQuery('#AdmMenuParent').select2();
                jQuery('#AdmMenuIcon').select2({formatResult: fnFormatSelect2WithIcons, formatSelection: fnFormatSelect2WithIcons});

                jQuery('#modalSave').modal({show: 'true', backdrop: 'static'});
                $('#modalSave').on('shown.bs.modal', function() {
                    jQuery('#AdmMenuName').focus();
                });
            },
            error: function(response, status, error) {
                bittionAjaxErrorHandler(response, status, error);
            }
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: fill a html select
    function fnFillHtmlSelect(select, data) {
        $.each(data, function(index, value) {
            select.append('<option value="'
                    + index
                    + '">'
                    + value
                    + '</option>');
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: fill a html select with an object anidated
    function fnFillHtmlSelectObject(select, data) {
        $.each(data, function(index, value) {
            select.append('<option value="'
                    + value['AdmMenu']['id']
                    + '">'
                    + value['AdmMenu']['name']
                    + '</option>');
        });
    }

//    Created: 23/08/2014 | Developer: reyro | Description: gives format to a select2 with icons
    function fnFormatSelect2WithIcons(option) {
        if (!option.id)
            return option.text; // optgroup
        else
            return "<i class='fa " + option.id + "'></i> " + option.text;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: generates menu
    function fnGenerateHtmlMenu(data) {
        var content = "<button id='create-0' class='btn btn-success btn-circle btnCreate' title='Crear hijo'><i class='fa fa-plus'></i></button>";
        var html = '<ul>';
        html += '<li>';
        html += '<a href="#" data-content="' + content + '" class="btn btn-success btn-sm menu-buttons"><i class="fa fa-sitemap"></i></a>';
        html += '<ul>';
        html += fnGenerateLevel1(data);
        html += '</ul>';
        html += '</li>';
        html += '</ul>';
        return html;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: generates first level
    function fnGenerateLevel1(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<a href="#" data-content="';
            html += "<button id='create-" + value['menu']['id'] + "' class='btn btn-success btn-circle btnCreate' title='Crear hijo'><i class='fa fa-plus'></i></button>";
            html += " <button id='update-1-" + value['menu']['id'] + "' class='btn btn-primary btn-circle btnUpdate' title='Editar'><i class='fa fa-pencil'></i></button>";
            html += " <button id='delete-1-" + value['menu']['id'] + "' class='btn btn-danger btn-circle btnDelete' title='Eliminar'><i class='fa fa-trash-o'></i></button>";
            html += '" class="btn btn-primary btn-sm menu-buttons"><i class="fa '+value['menu']['icon']+'"></i> ' + value['menu']['name'] + ' ('+value['menu']['order_menu']+')</a>';
            html += '<ul>';
            html += fnGenerateLevel2(value['children']);
            html += '</ul>';
            html += '</li>';
        });
        return html;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: generates second level    
    function fnGenerateLevel2(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<a href="#" data-content="';
//            html += "<button id='create-" + value['menu']['id'] + "' class='btn btn-success btn-circle btnCreate' title='Crear hijo'><i class='fa fa-plus'></i></button>";
            html += " <button id='update-2-" + value['menu']['id'] + "' class='btn btn-primary btn-circle btnUpdate' title='Editar'><i class='fa fa-pencil'></i></button>";
            html += " <button id='delete-2-" + value['menu']['id'] + "' class='btn btn-danger btn-circle btnDelete' title='Eliminar'><i class='fa fa-trash-o'></i></button>";
            html += '" class="btn btn-warning btn-sm menu-buttons"><i class="fa '+value['menu']['icon']+'"></i> ' + value['menu']['name'] + ' ('+value['menu']['order_menu']+')</a>';
            html += '<ul>';
//            html += fnGenerateLevel3(value['children']);
            html += '</ul>';
            html += '</li>';
        });
        return html;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: generates third level    
    function fnGenerateLevel3(data) {
        var html = '';
        $.each(data, function(index, value) {
            html += '<li>';
            html += '<a href="#" data-content="';
            html += "<button id='update-3-" + value['menu']['id'] + "' class='btn btn-primary btn-circle btnUpdate' title='Editar'><i class='fa fa-pencil'></i></button>";
            html += " <button id='delete-3-" + value['menu']['id'] + "' class='btn btn-danger btn-circle btnDelete' title='Eliminar'><i class='fa fa-trash-o'></i></button>";
            html += '" class="btn btn-default btn-sm menu-buttons"><i class="fa '+value['menu']['icon']+'"></i> ' + value['menu']['name'] + ' ('+value['menu']['order_menu']+')</a>';
            html += '</li>';
        });
        return html;
    }

//    Created: 23/08/2014 | Developer: reyro | Description: all icons from font awesome listed
    var fontAwesomeIcons = {
        "fa-adjust": "Adjust",
        "fa-adn": "Adn",
        "fa-align-center": "Align center",
        "fa-align-justify": "Align justify",
        "fa-align-left": "Align left",
        "fa-align-right": "Align right",
        "fa-ambulance": "Ambulance",
        "fa-anchor": "Anchor",
        "fa-android": "Android",
        "fa-angle-double-down": "Angle double down",
        "fa-angle-double-left": "Angle double left",
        "fa-angle-double-right": "Angle double right",
        "fa-angle-double-up": "Angle double up",
        "fa-angle-down": "Angle down",
        "fa-angle-left": "Angle left",
        "fa-angle-right": "Angle right",
        "fa-angle-up": "Angle up",
        "fa-apple": "Apple",
        "fa-archive": "Archive",
        "fa-arrow-circle-down": "Arrow circle down",
        "fa-arrow-circle-left": "Arrow circle left",
        "fa-arrow-circle-o-down": "Arrow circle o down",
        "fa-arrow-circle-o-left": "Arrow circle o left",
        "fa-arrow-circle-o-right": "Arrow circle o right",
        "fa-arrow-circle-o-up": "Arrow circle o up",
        "fa-arrow-circle-right": "Arrow circle right",
        "fa-arrow-circle-up": "Arrow circle up",
        "fa-arrow-down": "Arrow down",
        "fa-arrow-left": "Arrow left",
        "fa-arrow-right": "Arrow right",
        "fa-arrow-up": "Arrow up",
        "fa-arrows": "Arrows",
        "fa-arrows-alt": "Arrows alt",
        "fa-arrows-h": "Arrows h",
        "fa-arrows-v": "Arrows v",
        "fa-asterisk": "Asterisk",
        "fa-backward": "Backward",
        "fa-ban": "Ban",
        "fa-bar-chart-o": "Bar chart o",
        "fa-barcode": "Barcode",
        "fa-bars": "Bars",
        "fa-beer": "Beer",
        "fa-behance": "Behance",
        "fa-behance-square": "Behance square",
        "fa-bell": "Bell",
        "fa-bell-o": "Bell o",
        "fa-bitbucket": "Bitbucket",
        "fa-bitbucket-square": "Bitbucket square",
        "fa-bold": "Bold",
        "fa-bolt": "Bolt",
        "fa-bomb": "Bomb",
        "fa-book": "Book",
        "fa-bookmark": "Bookmark",
        "fa-bookmark-o": "Bookmark o",
        "fa-briefcase": "Briefcase",
        "fa-btc": "Btc",
        "fa-bug": "Bug",
        "fa-building": "Building",
        "fa-building-o": "Building o",
        "fa-bullhorn": "Bullhorn",
        "fa-bullseye": "Bullseye",
        "fa-calendar": "Calendar",
        "fa-calendar-o": "Calendar o",
        "fa-camera": "Camera",
        "fa-camera-retro": "Camera retro",
        "fa-car": "Car",
        "fa-caret-down": "Caret down",
        "fa-caret-left": "Caret left",
        "fa-caret-right": "Caret right",
        "fa-caret-square-o-down": "Caret square o down",
        "fa-caret-square-o-left": "Caret square o left",
        "fa-caret-square-o-right": "Caret square o right",
        "fa-caret-square-o-up": "Caret square o up",
        "fa-caret-up": "Caret up",
        "fa-certificate": "Certificate",
        "fa-chain-broken": "Chain broken",
        "fa-check": "Check",
        "fa-check-circle": "Check circle",
        "fa-check-circle-o": "Check circle o",
        "fa-check-square": "Check square",
        "fa-check-square-o": "Check square o",
        "fa-chevron-circle-down": "Chevron circle down",
        "fa-chevron-circle-left": "Chevron circle left",
        "fa-chevron-circle-right": "Chevron circle right",
        "fa-chevron-circle-up": "Chevron circle up",
        "fa-chevron-down": "Chevron down",
        "fa-chevron-left": "Chevron left",
        "fa-chevron-right": "Chevron right",
        "fa-chevron-up": "Chevron up",
        "fa-child": "Child",
        "fa-circle": "Circle",
        "fa-circle-o": "Circle o",
        "fa-circle-o-notch": "Circle o notch",
        "fa-circle-thin": "Circle thin",
        "fa-clipboard": "Clipboard",
        "fa-clock-o": "Clock o",
        "fa-cloud": "Cloud",
        "fa-cloud-download": "Cloud download",
        "fa-cloud-upload": "Cloud upload",
        "fa-code": "Code",
        "fa-code-fork": "Code fork",
        "fa-codepen": "Codepen",
        "fa-coffee": "Coffee",
        "fa-cog": "Cog",
        "fa-cogs": "Cogs",
        "fa-columns": "Columns",
        "fa-comment": "Comment",
        "fa-comment-o": "Comment o",
        "fa-comments": "Comments",
        "fa-comments-o": "Comments o",
        "fa-compass": "Compass",
        "fa-compress": "Compress",
        "fa-credit-card": "Credit card",
        "fa-crop": "Crop",
        "fa-crosshairs": "Crosshairs",
        "fa-css3": "Css3",
        "fa-cube": "Cube",
        "fa-cubes": "Cubes",
        "fa-cutlery": "Cutlery",
        "fa-database": "Database",
        "fa-delicious": "Delicious",
        "fa-desktop": "Desktop",
        "fa-deviantart": "Deviantart",
        "fa-digg": "Digg",
        "fa-dot-circle-o": "Dot circle o",
        "fa-download": "Download",
        "fa-dribbble": "Dribbble",
        "fa-dropbox": "Dropbox",
        "fa-drupal": "Drupal",
        "fa-eject": "Eject",
        "fa-ellipsis-h": "Ellipsis h",
        "fa-ellipsis-v": "Ellipsis v",
        "fa-empire": "Empire",
        "fa-envelope": "Envelope",
        "fa-envelope-o": "Envelope o",
        "fa-envelope-square": "Envelope square",
        "fa-eraser": "Eraser",
        "fa-eur": "Eur",
        "fa-exchange": "Exchange",
        "fa-exclamation": "Exclamation",
        "fa-exclamation-circle": "Exclamation circle",
        "fa-exclamation-triangle": "Exclamation triangle",
        "fa-expand": "Expand",
        "fa-external-link": "External link",
        "fa-external-link-square": "External link square",
        "fa-eye": "Eye",
        "fa-eye-slash": "Eye slash",
        "fa-facebook": "Facebook",
        "fa-facebook-square": "Facebook square",
        "fa-fast-backward": "Fast backward",
        "fa-fast-forward": "Fast forward",
        "fa-fax": "Fax",
        "fa-female": "Female",
        "fa-fighter-jet": "Fighter jet",
        "fa-file": "File",
        "fa-file-archive-o": "File archive o",
        "fa-file-audio-o": "File audio o",
        "fa-file-code-o": "File code o",
        "fa-file-excel-o": "File excel o",
        "fa-file-image-o": "File image o",
        "fa-file-o": "File o",
        "fa-file-pdf-o": "File pdf o",
        "fa-file-powerpoint-o": "File powerpoint o",
        "fa-file-text": "File text",
        "fa-file-text-o": "File text o",
        "fa-file-video-o": "File video o",
        "fa-file-word-o": "File word o",
        "fa-files-o": "Files o",
        "fa-film": "Film",
        "fa-filter": "Filter",
        "fa-fire": "Fire",
        "fa-fire-extinguisher": "Fire extinguisher",
        "fa-flag": "Flag",
        "fa-flag-checkered": "Flag checkered",
        "fa-flag-o": "Flag o",
        "fa-flask": "Flask",
        "fa-flickr": "Flickr",
        "fa-floppy-o": "Floppy o",
        "fa-folder": "Folder",
        "fa-folder-o": "Folder o",
        "fa-folder-open": "Folder open",
        "fa-folder-open-o": "Folder open o",
        "fa-font": "Font",
        "fa-forward": "Forward",
        "fa-foursquare": "Foursquare",
        "fa-frown-o": "Frown o",
        "fa-gamepad": "Gamepad",
        "fa-gavel": "Gavel",
        "fa-gbp": "Gbp",
        "fa-gift": "Gift",
        "fa-git": "Git",
        "fa-git-square": "Git square",
        "fa-github": "Github",
        "fa-github-alt": "Github alt",
        "fa-github-square": "Github square",
        "fa-gittip": "Gittip",
        "fa-glass": "Glass",
        "fa-globe": "Globe",
        "fa-google": "Google",
        "fa-google-plus": "Google plus",
        "fa-google-plus-square": "Google plus square",
        "fa-graduation-cap": "Graduation cap",
        "fa-h-square": "H square",
        "fa-hacker-news": "Hacker news",
        "fa-hand-o-down": "Hand o down",
        "fa-hand-o-left": "Hand o left",
        "fa-hand-o-right": "Hand o right",
        "fa-hand-o-up": "Hand o up",
        "fa-hdd-o": "Hdd o",
        "fa-header": "Header",
        "fa-headphones": "Headphones",
        "fa-heart": "Heart",
        "fa-heart-o": "Heart o",
        "fa-history": "History",
        "fa-home": "Home",
        "fa-hospital-o": "Hospital o",
        "fa-html5": "Html5",
        "fa-inbox": "Inbox",
        "fa-indent": "Indent",
        "fa-info": "Info",
        "fa-info-circle": "Info circle",
        "fa-inr": "Inr",
        "fa-instagram": "Instagram",
        "fa-italic": "Italic",
        "fa-joomla": "Joomla",
        "fa-jpy": "Jpy",
        "fa-jsfiddle": "Jsfiddle",
        "fa-key": "Key",
        "fa-keyboard-o": "Keyboard o",
        "fa-krw": "Krw",
        "fa-language": "Language",
        "fa-laptop": "Laptop",
        "fa-leaf": "Leaf",
        "fa-lemon-o": "Lemon o",
        "fa-level-down": "Level down",
        "fa-level-up": "Level up",
        "fa-life-ring": "Life ring",
        "fa-lightbulb-o": "Lightbulb o",
        "fa-link": "Link",
        "fa-linkedin": "Linkedin",
        "fa-linkedin-square": "Linkedin square",
        "fa-linux": "Linux",
        "fa-list": "List",
        "fa-list-alt": "List alt",
        "fa-list-ol": "List ol",
        "fa-list-ul": "List ul",
        "fa-location-arrow": "Location arrow",
        "fa-lock": "Lock",
        "fa-long-arrow-down": "Long arrow down",
        "fa-long-arrow-left": "Long arrow left",
        "fa-long-arrow-right": "Long arrow right",
        "fa-long-arrow-up": "Long arrow up",
        "fa-magic": "Magic",
        "fa-magnet": "Magnet",
        "fa-male": "Male",
        "fa-map-marker": "Map marker",
        "fa-maxcdn": "Maxcdn",
        "fa-medkit": "Medkit",
        "fa-meh-o": "Meh o",
        "fa-microphone": "Microphone",
        "fa-microphone-slash": "Microphone slash",
        "fa-minus": "Minus",
        "fa-minus-circle": "Minus circle",
        "fa-minus-square": "Minus square",
        "fa-minus-square-o": "Minus square o",
        "fa-mobile": "Mobile",
        "fa-money": "Money",
        "fa-moon-o": "Moon o",
        "fa-music": "Music",
        "fa-openid": "Openid",
        "fa-outdent": "Outdent",
        "fa-pagelines": "Pagelines",
        "fa-paper-plane": "Paper plane",
        "fa-paper-plane-o": "Paper plane o",
        "fa-paperclip": "Paperclip",
        "fa-paragraph": "Paragraph",
        "fa-pause": "Pause",
        "fa-paw": "Paw",
        "fa-pencil": "Pencil",
        "fa-pencil-square": "Pencil square",
        "fa-pencil-square-o": "Pencil square o",
        "fa-phone": "Phone",
        "fa-phone-square": "Phone square",
        "fa-picture-o": "Picture o",
        "fa-pied-piper": "Pied piper",
        "fa-pied-piper-alt": "Pied piper alt",
        "fa-pinterest": "Pinterest",
        "fa-pinterest-square": "Pinterest square",
        "fa-plane": "Plane",
        "fa-play": "Play",
        "fa-play-circle": "Play circle",
        "fa-play-circle-o": "Play circle o",
        "fa-plus": "Plus",
        "fa-plus-circle": "Plus circle",
        "fa-plus-square": "Plus square",
        "fa-plus-square-o": "Plus square o",
        "fa-power-off": "Power off",
        "fa-print": "Print",
        "fa-puzzle-piece": "Puzzle piece",
        "fa-qq": "Qq",
        "fa-qrcode": "Qrcode",
        "fa-question": "Question",
        "fa-question-circle": "Question circle",
        "fa-quote-left": "Quote left",
        "fa-quote-right": "Quote right",
        "fa-random": "Random",
        "fa-rebel": "Rebel",
        "fa-recycle": "Recycle",
        "fa-reddit": "Reddit",
        "fa-reddit-square": "Reddit square",
        "fa-refresh": "Refresh",
        "fa-renren": "Renren",
        "fa-repeat": "Repeat",
        "fa-reply": "Reply",
        "fa-reply-all": "Reply all",
        "fa-retweet": "Retweet",
        "fa-road": "Road",
        "fa-rocket": "Rocket",
        "fa-rss": "Rss",
        "fa-rss-square": "Rss square",
        "fa-rub": "Rub",
        "fa-scissors": "Scissors",
        "fa-search": "Search",
        "fa-search-minus": "Search minus",
        "fa-search-plus": "Search plus",
        "fa-share": "Share",
        "fa-share-alt": "Share alt",
        "fa-share-alt-square": "Share alt square",
        "fa-share-square": "Share square",
        "fa-share-square-o": "Share square o",
        "fa-shield": "Shield",
        "fa-shopping-cart": "Shopping cart",
        "fa-sign-in": "Sign in",
        "fa-sign-out": "Sign out",
        "fa-signal": "Signal",
        "fa-sitemap": "Sitemap",
        "fa-skype": "Skype",
        "fa-slack": "Slack",
        "fa-sliders": "Sliders",
        "fa-smile-o": "Smile o",
        "fa-sort": "Sort",
        "fa-sort-alpha-asc": "Sort alpha asc",
        "fa-sort-alpha-desc": "Sort alpha desc",
        "fa-sort-amount-asc": "Sort amount asc",
        "fa-sort-amount-desc": "Sort amount desc",
        "fa-sort-asc": "Sort asc",
        "fa-sort-desc": "Sort desc",
        "fa-sort-numeric-asc": "Sort numeric asc",
        "fa-sort-numeric-desc": "Sort numeric desc",
        "fa-soundcloud": "Soundcloud",
        "fa-space-shuttle": "Space shuttle",
        "fa-spinner": "Spinner",
        "fa-spoon": "Spoon",
        "fa-spotify": "Spotify",
        "fa-square": "Square",
        "fa-square-o": "Square o",
        "fa-stack-exchange": "Stack exchange",
        "fa-stack-overflow": "Stack overflow",
        "fa-star": "Star",
        "fa-star-half": "Star half",
        "fa-star-half-o": "Star half o",
        "fa-star-o": "Star o",
        "fa-steam": "Steam",
        "fa-steam-square": "Steam square",
        "fa-step-backward": "Step backward",
        "fa-step-forward": "Step forward",
        "fa-stethoscope": "Stethoscope",
        "fa-stop": "Stop",
        "fa-strikethrough": "Strikethrough",
        "fa-stumbleupon": "Stumbleupon",
        "fa-stumbleupon-circle": "Stumbleupon circle",
        "fa-subscript": "Subscript",
        "fa-suitcase": "Suitcase",
        "fa-sun-o": "Sun o",
        "fa-superscript": "Superscript",
        "fa-table": "Table",
        "fa-tablet": "Tablet",
        "fa-tachometer": "Tachometer",
        "fa-tag": "Tag",
        "fa-tags": "Tags",
        "fa-tasks": "Tasks",
        "fa-taxi": "Taxi",
        "fa-tencent-weibo": "Tencent weibo",
        "fa-terminal": "Terminal",
        "fa-text-height": "Text height",
        "fa-text-width": "Text width",
        "fa-th": "Th",
        "fa-th-large": "Th large",
        "fa-th-list": "Th list",
        "fa-thumb-tack": "Thumb tack",
        "fa-thumbs-down": "Thumbs down",
        "fa-thumbs-o-down": "Thumbs o down",
        "fa-thumbs-o-up": "Thumbs o up",
        "fa-thumbs-up": "Thumbs up",
        "fa-ticket": "Ticket",
        "fa-times": "Times",
        "fa-times-circle": "Times circle",
        "fa-times-circle-o": "Times circle o",
        "fa-tint": "Tint",
        "fa-trash-o": "Trash o",
        "fa-tree": "Tree",
        "fa-trello": "Trello",
        "fa-trophy": "Trophy",
        "fa-truck": "Truck",
        "fa-try": "Try",
        "fa-tumblr": "Tumblr",
        "fa-tumblr-square": "Tumblr square",
        "fa-twitter": "Twitter",
        "fa-twitter-square": "Twitter square",
        "fa-umbrella": "Umbrella",
        "fa-underline": "Underline",
        "fa-undo": "Undo",
        "fa-university": "University",
        "fa-unlock": "Unlock",
        "fa-unlock-alt": "Unlock alt",
        "fa-upload": "Upload",
        "fa-usd": "Usd",
        "fa-user": "User",
        "fa-user-md": "User md",
        "fa-users": "Users",
        "fa-video-camera": "Video camera",
        "fa-vimeo-square": "Vimeo square",
        "fa-vine": "Vine",
        "fa-vk": "Vk",
        "fa-volume-down": "Volume down",
        "fa-volume-off": "Volume off",
        "fa-volume-up": "Volume up",
        "fa-weibo": "Weibo",
        "fa-weixin": "Weixin",
        "fa-wheelchair": "Wheelchair",
        "fa-windows": "Windows",
        "fa-wordpress": "Wordpress",
        "fa-wrench": "Wrench",
        "fa-xing": "Xing",
        "fa-xing-square": "Xing square",
        "fa-yahoo": "Yahoo",
        "fa-youtube": "Youtube",
        "fa-youtube-play": "Youtube play",
        "fa-youtube-square": "Youtube square"
    };

//END SCRIPT
});