<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php $cakeDescription = __d('biteran', 'ERP Biteran '); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
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
        <?php echo $this->element('SmartAdminStyle'); ?>
        <!-- END STYLE: css, icons and images  -->
    </head>       

    <body class="smart-style-2">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->

        <!-- START HEADER -->
        <?php echo $this->element('SmartAdminHeader'); ?>
        <!-- END HEADER -->

        <!-- START LEFT PANEL : Navigation area -->
        <?php echo $this->element('SmartAdminLeftPanel'); ?>
        <!-- END LEFT PANEL: Navigation area -->

        <!-- MAIN PANEL -->
        <div id="main" role="main">

            <!-- START RIBBON -->
            <?php echo $this->element('SmartAdminRibbon'); ?>
            <!-- END RIBBON -->

            <!-- START CONTENT -->
            <div id="content">               
                <?php 
                echo $this->Session->flash('auth'); //Authentication for login or isAuthorized
                echo $this->Session->flash();//default
                ?>
                <?php echo '<div style="display:none;">' . $this->Session->flash('flashGrowl') . '</div>'; // emulates ajax growl message when post a form, must add flashGrowlMessage.js on the view?>
                <?php echo $this->fetch('content'); ?>
                <?php echo $this->element('sql_dump'); ?>
            </div>
            <!-- END CONTENT -->

        </div>
        <!-- END MAIN PANEL -->

        <!-- START PAGE FOOTER -->
        <?php echo $this->element('SmartAdminFooter'); ?>
        <!-- END PAGE FOOTER -->

        <!-- START JAVASCRIPT -->
        <?php echo $this->element('SmartAdminJavascript'); ?>        
        <!-- END JAVASCRIPT -->
    </body>

</html>