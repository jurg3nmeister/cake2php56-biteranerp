<?php $cakeDescription = __d('cake_dev', 'admin_module'); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!--        <meta charset="utf-8">-->
        <?php echo $this->Html->charset(); ?>
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

<!--        <title> SmartAdmin </title>-->
        <title>
            <?php echo $cakeDescription; ?>
            <?php echo $title_for_layout; ?>
        </title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <!--        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">-->
        <?php echo $this->Html->css('bootstrap.min'); ?>
        <?php echo $this->Html->css('font-awesome.min'); ?>

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <!--        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
                <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">-->
        <?php echo $this->Html->css('smartadmin-production.min'); ?>
        <?php echo $this->Html->css('smartadmin-skins.min'); ?>

        <!-- SmartAdmin RTL Support is under construction-->
        <!--<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css">-->
        <?php echo $this->Html->css('smartadmin-rtl.min'); ?>

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->
        <?php echo $this->Html->css('bittion_style');?>

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <!--<link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">-->
        <?php echo $this->Html->css('demo.min');  //ONLY DEMO?>
        <?php
        //here goes css created on the views
        //echo $this->fetch('css'); 
        ?>


        <!-- FAVICONS -->
        <link rel="shortcut icon" href="<?php echo $this->webroot; ?>img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo $this->webroot; ?>img/favicon/favicon.ico" type="image/x-icon">

        <!-- GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="<?php echo $this->webroot; ?>img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->webroot; ?>img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->webroot; ?>img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->webroot; ?>img/splash/touch-icon-ipad-retina.png">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo $this->webroot; ?>img/splash/iphone.png" media="screen and (max-device-width: 320px)">
    </head>       

    <body>
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">

                <!-- PLACE YOUR LOGO HERE -->
                <span id="logo"> 
                    <?php echo $this->Html->image('logo.png', array('alt' => 'SmartAdmin')); ?>
                </span>
                <!-- END LOGO PLACEHOLDER -->

                <!-- Note: The activity badge color changes when clicked and resets the number to 0
                Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
                <span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>

                <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
                <div class="ajax-dropdown">

                    <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/mail.html">
                            Msgs (14) </label>
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/notifications.html">
                            notify (3) </label>
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/tasks.html">
                            Tasks (4) </label>
                    </div>

                    <!-- notification content -->
                    <div class="ajax-notifications custom-scroll">

                        <div class="alert alert-transparent">
                            <h4>Click a button to show messages here</h4>
                            This blank page message helps protect your privacy, or you can show the first message here automatically.
                        </div>

                        <i class="fa fa-lock fa-4x fa-border"></i>

                    </div>
                    <!-- end notification content -->

                    <!-- footer: refresh area -->
                    <span> Last updated on: 12/12/2013 9:43AM
                        <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
                            <i class="fa fa-refresh"></i>
                        </button> 
                    </span>
                    <!-- end footer -->

                </div>
                <!-- END AJAX-DROPDOWN -->
            </div>

            <!-- projects dropdown -->
            <div class="project-context hidden-xs">

                <span class="label">Projects:</span>
                <span class="project-selector dropdown-toggle" data-toggle="dropdown">Recent projects <i class="fa fa-angle-down"></i></span>

                <!-- Suggestion: populate this list with fetch and push technique -->
                <ul class="dropdown-menu">
                    <li>
                        <a href="javascript:void(0);">Online e-merchant management system - attaching integration with the iOS</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">Notes on pipeline upgradee</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">Assesment Report for merchant account</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:void(0);"><i class="fa fa-power-off"></i> Clear</a>
                    </li>
                </ul>
                <!-- end dropdown-menu-->

            </div>
            <!-- end projects dropdown -->

            <!-- pulled right: nav area -->
            <div class="pull-right">

                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->

                <!-- #MOBILE -->
                <!-- Top menu profile link : this shows only when top menu is active -->
                <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                    <li class="">
                        <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
                            <?php echo $this->Html->image('avatars/sunny.png', array("alt" => "John Doe", "class" => "online")); ?>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="login.html" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <!-- end logout button -->

                <!-- search mobile button (this is hidden till mobile view port) -->
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <!-- end search mobile button -->

                <!-- input: search field -->
                <form action="search.html" class="header-search pull-right">
                    <input id="search-fld"  type="text" name="param" placeholder="Find reports and more" data-autocomplete='[
                           "ActionScript",
                           "AppleScript",
                           "Asp",
                           "BASIC",
                           "C",
                           "C++",
                           "Clojure",
                           "COBOL",
                           "ColdFusion",
                           "Erlang",
                           "Fortran",
                           "Groovy",
                           "Haskell",
                           "Java",
                           "JavaScript",
                           "Lisp",
                           "Perl",
                           "PHP",
                           "Python",
                           "Ruby",
                           "Scala",
                           "Scheme"]'>
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
                </form>
                <!-- end input: search field -->

                <!-- fullscreen button -->
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
                <!-- end fullscreen button -->

                <!-- multiple lang dropdown : find all flags in the flags page -->
                <ul class="header-dropdown-list hidden-xs">
                    <li>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->Html->image('blank.gif', array("alt" => "United States", "class" => "flag flag-us")); ?> <span> US </span> <i class="fa fa-angle-down"></i> </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="active">
                                <a href="javascript:void(0);"><?php echo $this->Html->image('blank.gif', array("alt" => "United States", "class" => "flag flag-us")); ?> US</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><?php echo $this->Html->image('blank.gif', array("alt" => "Spanish", "class" => "flag flag-es")); ?> Spanish</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);"><?php echo $this->Html->image('blank.gif', array("alt" => "German", "class" => "flag flag-de")); ?> German</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- end multiple lang -->

            </div>
            <!-- end pulled right: nav area -->

        </header>
        <!-- END HEADER -->

        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <aside id="left-panel">

            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as it --> 

                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <?php echo $this->Html->image('avatars/sunny.png', array("alt" => "me", "class" => "online")); ?>
                        <span>
                            john.doe 
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 

                </span>
            </div>
            <!-- end user info -->

            <!-- NAVIGATION : This navigation is also responsive

            To make this navigation dynamic please make sure to link the node
            (the reference to the nav > ul) after page load. Or the navigation
            will not initialize.
            -->
            <nav>
                    <!-- NOTE: Notice the gaps after each icon usage <i></i>..
                    Please note that these links work a bit different than
                    traditional href="" links. See documentation for details.
                -->

                <ul>
                    <li class="active">
                        <a href="index.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span><span class="badge pull-right inbox-badge">14</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-bar-chart-o"></i> <span class="menu-item-parent">Graphs</span></a>
                        <ul>
                            <li>
                                <a href="flot.html">Flot Chart</a>
                            </li>
                            <li>
                                <a href="morris.html">Morris Charts</a>
                            </li>
                            <li>
                                <a href="inline-charts.html">Inline Charts</a>
                            </li>
                            <li>
                                <a href="dygraphs.html">Dygraphs <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-table"></i> <span class="menu-item-parent">Tables</span></a>
                        <ul>
                            <li>
                                <a href="table.html">Normal Tables</a>
                            </li>
                            <li>
                                <a href="datatables.html">Data Tables <span class="badge inbox-badge bg-color-greenLight">v1.10</span></a>
                            </li>
                            <li>
                                <a href="jqgrid.html">Jquery Grid</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">Forms</span></a>
                        <ul>
                            <li>
                                <a href="form-elements.html">Smart Form Elements</a>
                            </li>
                            <li>
                                <a href="form-templates.html">Smart Form Layouts</a>
                            </li>
                            <li>
                                <a href="validation.html">Smart Form Validation</a>
                            </li>
                            <li>
                                <a href="bootstrap-forms.html">Bootstrap Form Elements</a>
                            </li>
                            <li>
                                <a href="plugins.html">Form Plugins</a>
                            </li>
                            <li>
                                <a href="wizard.html">Wizards</a>
                            </li>
                            <li>
                                <a href="other-editors.html">Bootstrap Editors</a>
                            </li>
                            <li>
                                <a href="dropzone.html">Dropzone </a>
                            </li>
                            <li>
                                <a href="image-editor.html">Image Cropping <span class="badge pull-right inbox-badge bg-color-yellow">new</span></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-desktop"></i> <span class="menu-item-parent">UI Elements</span></a>
                        <ul>
                            <li>
                                <a href="general-elements.html">General Elements</a>
                            </li>
                            <li>
                                <a href="buttons.html">Buttons</a>
                            </li>
                            <li>
                                <a href="#">Icons</a>
                                <ul>
                                    <li>
                                        <a href="fa.html"><i class="fa fa-plane"></i> Font Awesome</a>
                                    </li>	
                                    <li>
                                        <a href="glyph.html"><i class="glyphicon glyphicon-plane"></i> Glyph Icons</a>
                                    </li>	
                                    <li>
                                        <a href="flags.html"><i class="fa fa-flag"></i> Flags</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="grid.html">Grid</a>
                            </li>
                            <li>
                                <a href="treeview.html">Tree View</a>
                            </li>
                            <li>
                                <a href="nestable-list.html">Nestable Lists</a>
                            </li>
                            <li>
                                <a href="jqui.html">JQuery UI</a>
                            </li>
                            <li>
                                <a href="typography.html">Typography</a>
                            </li>
                            <li>
                                <a href="#">Six Level Menu</a>
                                <ul>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #2</a>
                                        <ul>
                                            <li>
                                                <a href="#"><i class="fa fa-fw fa-folder-open"></i> Sub #2.1 </a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> Item #2.1.1</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-plus"></i> Expand</a>
                                                        <ul>
                                                            <li>
                                                                <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                            </li>
                                                            <li>
                                                                <a href="#"><i class="fa fa-fw fa-trash-o"></i> Delete</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-fw fa-folder-open"></i> Item #3</a>

                                        <ul>
                                            <li>
                                                <a href="#"><i class="fa fa-fw fa-folder-open"></i> 3ed Level </a>
                                                <ul>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"><i class="fa fa-fw fa-file-text"></i> File</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>

                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="calendar.html"><i class="fa fa-lg fa-fw fa-calendar"><em>3</em></i> <span class="menu-item-parent">Calendar</span></a>
                    </li>
                    <li>
                        <a href="widgets.html"><i class="fa fa-lg fa-fw fa-list-alt"></i> <span class="menu-item-parent">Widgets</span></a>
                    </li>
                    <li>
                        <a href="gallery.html"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Gallery</span></a>
                    </li>
                    <li>
                        <a href="gmap-xml.html"><i class="fa fa-lg fa-fw fa-map-marker"></i> <span class="menu-item-parent">GMap Skins</span><span class="badge bg-color-greenLight pull-right inbox-badge">9</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-lg fa-fw fa-windows"></i> <span class="menu-item-parent">Miscellaneous</span></a>
                        <ul>
                            <li>
                                <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> Other Pages</a>
                                <ul>
                                    <li>
                                        <a href="forum.html">Forum Layout</a>
                                    </li>
                                    <li>
                                        <a href="profile.html">Profile</a>
                                    </li>
                                    <li>
                                        <a href="timeline.html">Timeline</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="pricing-table.html">Pricing Tables</a>
                            </li>
                            <li>
                                <a href="invoice.html">Invoice</a>
                            </li>
                            <li>
                                <a href="login.html" target="_top">Login</a>
                            </li>
                            <li>
                                <a href="register.html" target="_top">Register</a>
                            </li>
                            <li>
                                <a href="lock.html" target="_top">Locked Screen</a>
                            </li>
                            <li>
                                <a href="error404.html">Error 404</a>
                            </li>
                            <li>
                                <a href="error500.html">Error 500</a>
                            </li>
                            <li>
                                <a href="blank_.html">Blank Page</a>
                            </li>
                            <li>
                                <a href="email-template.html">Email Template</a>
                            </li>
                            <li>
                                <a href="search.html">Search Page</a>
                            </li>
                            <li>
                                <a href="ckeditor.html">CK Editor</a>
                            </li>
                        </ul>
                    </li>
                    <li class="top-menu-hidden">
                        <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">SmartAdmin Intel</span></a>
                        <ul>
                            <li>
                                <a href="difver.html"><i class="fa fa-stack-overflow"></i> Different Versions</a>
                            </li>
                            <li>
                                <a href="applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                            </li>
                            <li>
                                <a href="http://bootstraphunter.com/smartadmin/BUGTRACK/track_/documentation/index.html" target="_blank"><i class="fa fa-book"></i> Documentation</a>
                            </li>
                            <li>
                                <a href="http://bootstraphunter.com/smartadmin/BUGTRACK/track_/" target="_blank"><i class="fa fa-bug"></i> Bug Tracker</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <span class="minifyme" data-action="minifyMenu"> 
                <i class="fa fa-arrow-circle-left hit"></i> 
            </span>

        </aside>
        <!-- END NAVIGATION -->

        <!-- MAIN PANEL -->
        <div id="main" role="main">

            <!-- RIBBON -->
            <div id="ribbon">

                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
                        <i class="fa fa-refresh"></i>
                    </span> 
                </span>

                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li>Home</li><li>Dashboard</li>
                </ol>
                <!-- end breadcrumb -->

                <!-- You can also add more buttons to the
                ribbon for further usability

                Example below:

                <span class="ribbon-button-alignment pull-right">
                <span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
                <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
                <span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
                </span> -->

            </div>
            <!-- END RIBBON -->

            <!-- MAIN CONTENT -->
            <div id="content">

                <!--aqui va el contenido-->
                <?php echo $this->Session->flash(); ?>
                <?php echo '<div style="display:none;">'.$this->Session->flash('flashGrowl').'</div>'; // emulate ajax growl message when post a form?>
                
                <?php echo $this->fetch('content'); ?>
                <?php echo $this->element('sql_dump'); ?>

            </div>
            <!-- END MAIN CONTENT -->

        </div>
        <!-- END MAIN PANEL -->

        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white">SmartAdmin WebApp © 2013-2014</span>
                </div>

                <div class="col-xs-6 col-sm-6 text-right hidden-xs">
                    <div class="txt-color-white inline-block">
                        <i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong>52 mins ago &nbsp;</strong> </i>
                        <div class="btn-group dropup">
                            <button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
                                <i class="fa fa-link"></i> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right text-left">
                                <li>
                                    <div class="padding-5">
                                        <p class="txt-color-darken font-sm no-margin">Download Progress</p>
                                        <div class="progress progress-micro no-margin">
                                            <div class="progress-bar progress-bar-success" style="width: 50%;"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="padding-5">
                                        <p class="txt-color-darken font-sm no-margin">Server Load</p>
                                        <div class="progress progress-micro no-margin">
                                            <div class="progress-bar progress-bar-success" style="width: 20%;"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="padding-5">
                                        <p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
                                        <div class="progress progress-micro no-margin">
                                            <div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="padding-5">
                                        <button class="btn btn-block btn-default">refresh</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE FOOTER -->

        <!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
            Note: These tiles are completely responsive,
            you can add as many as you like
        -->
        <div id="shortcut">
            <ul>
                <li>
                    <a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
                </li>
                <li>
                    <a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
                </li>
                <li>
                    <a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
                </li>
                <li>
                    <a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
                </li>
                <li>
                    <a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
                </li>
            </ul>
        </div>
        <!-- END SHORTCUT AREA -->

        
        <!--=======================JAVASCRIPT=========================== -->
        <!-- Aumente / a cada script que no tiene el php de cake en src ej "/admin_bittion/js/.." para que funcione con cakephp-->

        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->

        <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo $this->webroot; ?>js/plugin/pace/pace.min.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo $this->webroot; ?>js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>

        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo $this->webroot; ?>js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>

        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
        <script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

        <!-- BOOTSTRAP JS -->
        <!--<script src="js/bootstrap/bootstrap.min.js"></script>-->
        <?php echo $this->Html->script('bootstrap/bootstrap.min'); ?>

        <!-- BITTION - BITTION MAIN-->
        <?php echo $this->Html->script('bittion/BittionMain'); ?>
        
        <!-- BITTION - BITTION ALERT MODAL-->
        <?php echo $this->Html->script('bittion/bittionAlertModal'); ?>

        <!-- CUSTOM NOTIFICATION -->
        <!--<script src="js/notification/SmartNotification.min.js"></script>-->
        <?php echo $this->Html->script('notification/SmartNotification.min'); ?>

        <!-- JARVIS WIDGETS -->
        <!--<script src="js/smartwidgets/jarvis.widget.min.js"></script>-->
        <?php echo $this->Html->script('smartwidgets/jarvis.widget.min'); ?>

        <!-- EASY PIE CHARTS -->
        <!--<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>-->
        <?php echo $this->Html->script('plugin/easy-pie-chart/jquery.easy-pie-chart.min'); ?>

        <!-- SPARKLINES -->
        <!--<script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>-->
        <?php echo $this->Html->script('plugin/sparkline/jquery.sparkline.min'); ?>

        <!-- JQUERY VALIDATE -->
        <!--<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>-->
        <?php echo $this->Html->script('plugin/jquery-validate/jquery.validate.min'); ?>

        <!-- JQUERY MASKED INPUT -->
        <!--<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>-->
        <?php echo $this->Html->script('plugin/masked-input/jquery.maskedinput.min'); ?>

        <!-- JQUERY SELECT2 INPUT -->
        <!--<script src="js/plugin/select2/select2.min.js"></script>-->
        <?php echo $this->Html->script('plugin/select2/select2.min'); ?>

        <!-- JQUERY UI + Bootstrap Slider -->
        <!--<script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>-->
        <?php echo $this->Html->script('plugin/bootstrap-slider/bootstrap-slider.min'); ?>
        
        <!-- browser msie issue fix -->
        <!--<script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>-->
        <?php echo $this->Html->script('plugin/msie-fix/jquery.mb.browser.min'); ?>

        <!-- FastClick: For mobile devices -->
        <!--<script src="js/plugin/fastclick/fastclick.min.js"></script>-->
        <?php echo $this->Html->script('plugin/fastclick/fastclick.min'); ?>

        <!--[if IE 8]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

        <![endif]-->

        <!-- Demo purpose only - Cambia los themes-->
<!--        <script src="js/demo.min.js"></script>-->
        <?php echo $this->Html->script('demo.min'); ?>

        <!-- MAIN APP JS FILE -->
        <!--<script src="js/app.min.js"></script>-->
        <?php echo $this->Html->script('app.min'); ?> <!-- editar esto, ya que da nombres a widgets por defecto Ej:Standar Data tables wid-id-0-->



        <script>
            $(document).ready(function() {
                // DO NOT REMOVE : GLOBAL FUNCTIONS!
                pageSetUp();

                /*
                 * PAGE RELATED SCRIPTS
                 */
//                $(".js-status-update a").click(function() {
//                    var selText = $(this).text();
//                    var $this = $(this);
//                    $this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
//                    $this.parents('.dropdown-menu').find('li').removeClass('active');
//                    $this.parent().addClass('active');
//                });
            });
        </script>
        <?php
        //here goes scripts created on the views
        echo $this->fetch('script');
        ?>

<!--         Your GOOGLE ANALYTICS CODE Below 
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        </script>-->


    </body>
</html>