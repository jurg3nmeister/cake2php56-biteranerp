<?php /* (c)Bittion Admin Module | Created: 13/10/2014 | Developer:reyro */ ?>
<?php $cakeDescription = __d('bittion', 'ERP Biteran '); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo $this->webroot; ?>js/libs/jquery-2.0.2.min.js"><\/script>');
            }
            $(window).load(function () {
                $("#loading-page").remove();
            });
        </script>

        <?php echo $this->Html->charset(); ?>
        <title>
            <?php
            echo $cakeDescription;
            echo $title_for_layout;
            ?>
        </title>
        <!--<meta charset="utf-8">-->
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php
        //IE=edge => render tells Internet Explorer to use the highest mode available to that version of IE. Could be IE=9; IE=8; IE=7. Forces the browser to render as that particular version's standards
        //chrome=1 => Its for Google's Chrome Frame browser add-on.ChromeFrame can be installed on various versions of IE. Activates chrome frames if it exists.
        ?>
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->



        <!-- START STYLE: css, icons and images -->
        <?php // echo $this->element('SmartAdminStyle'); ?>
        <?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
        <!-- Basic Styles -->
        <?php echo $this->Html->css('bootstrap.min'); ?>
        <?php echo $this->Html->css('font-awesome.min'); ?>



        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <?php // echo $this->Html->css('smartadmin-production.min'); ?>
        <?php // echo $this->Html->css('smartadmin-skins.min'); ?>

        <!-- SmartAdmin RTL Support is under construction-->
        <?php // echo $this->Html->css('smartadmin-rtl.min'); ?>

        <!-- Bittion Style, to override SmartAdmin and retain customization with each update.
        <?php // echo $this->Html->css('bittion_style');   ?>
        
        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <?php // echo $this->Html->css('demo.min');  //ONLY DEMO?>
        <?php
//here goes css created on the views
//echo $this->fetch('css'); 
        ?>
        <?php echo $this->Html->css('bittion_style');  //ONLY DEMO?>

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
        <!-- END STYLE: css, icons and images  -->
        <script>
            document.write('<div id="loading-page" style="position:fixed; background-color: #eaeaea; height:100%; width:100%; z-index:10; top:0; left:0; text-align: center;vertical-align: middle;"><span style="top:50%;position: relative;transform: translateY(-50%); font-size: 24px;"><i class="fa fa-gear fa-4x fa-spin"></i><br>Cargando....</span></div>');
        </script>

    </head>       

    <body style="background-color:#3A3633;">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

        <!-- START HEADER -->
        <?php // echo $this->element('SmartAdminHeader'); ?>
        <!-- END HEADER -->

        <!-- START LEFT PANEL : Navigation area -->
        <?php // echo $this->element('SmartAdminLeftPanel'); ?>
        <!-- END LEFT PANEL: Navigation area -->

        <!-- MAIN PANEL -->
        <div id="main" role="main">

            <!-- START RIBBON -->
            <?php // echo $this->element('SmartAdminRibbon'); ?>
            <!-- END RIBBON -->

            <!-- START CONTENT -->
            <div id="content">        
                <?php
                echo $this->Session->flash('auth'); //Authentication for login or isAuthorized
                echo $this->Session->flash(); //default
                ?>
                <?php echo '<div style="display:none;">' . $this->Session->flash('flashGrowl') . '</div>'; // emulates ajax growl message when post a form, must add flashGrowlMessage.js on the view?>
                <!-- ////////////////////////// START - VIEWS CONTENT(CORE) //////////////////-->
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hidden-print" style="background-color:#3A3633;text-align:center;"><a href="javascript:window.print()" id="btnPrint" class="btn btn-primary noPrint" style="margin-top: 10px;margin-bottom: 10px;"><i class="fa fa-print"></i> Imprimir</a></div>
                <div class="hidden-xs hidden-sm col-md-1 col-lg-2 hidden-print"></div>
                <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 " id="printArea" style="background-color:#FFF;">
                    <!-- /////////////////////////// CONTENT START///////////////////////-->
                    <?php echo $this->fetch('content'); ?>
                    <div class="hidden-print">
                        <?php echo $this->element('sql_dump'); ?>
                    </div>
                    <!-- /////////////////////////// CONTENT END///////////////////////-->
                </div>
                <div class="hidden-xs hidden-sm col-md-1 col-lg-2 hidden-print"></div>
                <!-- ////////////////////////// END - VIEWS CONTENT(CORE) //////////////////-->

            </div>
            <!-- END CONTENT -->

        </div>
        <!-- END MAIN PANEL -->

        <!-- START PAGE FOOTER -->
        <?php // echo $this->element('SmartAdminFooter'); ?>
        <!-- END PAGE FOOTER -->

        <!-- START JAVASCRIPT -->
        <?php // echo $this->element('SmartAdminJavascript'); ?>        
        <!-- END JAVASCRIPT -->
    </body>

</html>
