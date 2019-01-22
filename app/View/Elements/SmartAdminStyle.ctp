<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<!-- Basic Styles -->
<?php echo $this->Html->css('bootstrap.min'); ?>
<?php echo $this->Html->css('font-awesome.min'); ?>

<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
<?php echo $this->Html->css('smartadmin-production.min'); ?>
<?php echo $this->Html->css('smartadmin-skins.min'); ?>

<!-- SmartAdmin RTL Support is under construction-->
<?php echo $this->Html->css('smartadmin-rtl.min'); ?>

<!-- Bittion Style, to override SmartAdmin and retain customization with each update.
<?php // echo $this->Html->css('bittion_style');   ?>

<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
<?php // echo $this->Html->css('demo.min');  //ONLY DEMO?>
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