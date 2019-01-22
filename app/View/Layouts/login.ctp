<?php $cakeDescription = __d('bittion', 'ERP Biteran'); ?>
<!DOCTYPE html>
<html lang="es" id="extr-page">
    <head>
            <!--<meta charset="utf-8">-->
        <?php echo $this->Html->charset(); ?>
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
        <title>
            <?php echo $cakeDescription; ?>
            <?php echo $title_for_layout; ?>
        </title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <?php echo $this->Html->css('bootstrap.min'); ?>
        <?php echo $this->Html->css('font-awesome.min'); ?>

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <?php echo $this->Html->css('smartadmin-production.min'); ?>
        <?php echo $this->Html->css('smartadmin-skins.min'); ?>

        <!-- We recommend you use "your_style.css" to override SmartAdmin
             specific styles this will also ensure you retrain your customization with each SmartAdmin update.
        <?php // echo $this->Html->css('your_style');?>

        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
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
                <span id="logo">
                    <?php echo $this->Html->image('logo.png', array('alt' => 'SmartAdmin')); ?>
                </span>
            </div>
        </header>
        <!-- END HEADER -->

        <!-- MAIN PANEL -->
        <div id="main" role="main">

            <!-- MAIN CONTENT -->
            <div id="content" class="container">
                <!--aqui va el contenido-->
                <?php echo $this->fetch('content'); ?>
            </div>
            <!-- END MAIN CONTENT -->

        </div>
        <!-- END MAIN PANEL -->



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
        <?php echo $this->Html->script('bootstrap/bootstrap.min'); ?>

        <!-- JQUERY VALIDATE -->
        <?php echo $this->Html->script('plugin/jquery-validate/jquery.validate.min'); ?>

        <!-- JQUERY MASKED INPUT -->
        <?php echo $this->Html->script('plugin/masked-input/jquery.maskedinput.min'); ?>


        <!-- MAIN APP JS FILE -->
        <?php echo $this->Html->script('app.min'); ?>

        <?php
        //here goes scripts created on the views
        echo $this->fetch('script');
        ?>

        <script type="text/javascript">
//            runAllForms();

            $(function() {
                // Validation
                $("#AdmUserLoginForm").validate({
                    // Do not change code below
                    errorPlacement: function(error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
        </script>

    </body>
</html>