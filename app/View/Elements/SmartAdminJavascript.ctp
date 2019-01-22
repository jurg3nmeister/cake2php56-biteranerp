<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<!--<script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php // echo $this->webroot; ?>js/plugin/pace/pace.min.js"></script>-->

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

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events -->
<?php // echo $this->Html->script('plugin/jquery-touch/jquery.ui.touch-punch.min');  ?>

<!-- BOOTSTRAP JS -->
<?php echo $this->Html->script('bootstrap/bootstrap.min'); ?>

<!-- BITTION - BITTION MAIN-->
<?php echo $this->Html->script('bittion/BittionMain'); ?>

<!-- BITTION - BITTION ALERT MODAL-->
<?php echo $this->Html->script('bittion/bittionAlertModal'); ?>

<!-- CUSTOM NOTIFICATION -->
<?php echo $this->Html->script('notification/SmartNotification.min'); ?>

<!-- JARVIS WIDGETS -->
<?php echo $this->Html->script('smartwidgets/jarvis.widget.min'); ?>

<!-- EASY PIE CHARTS -->
<?php echo $this->Html->script('plugin/easy-pie-chart/jquery.easy-pie-chart.min'); ?>

<!-- SPARKLINES -->
<?php echo $this->Html->script('plugin/sparkline/jquery.sparkline.min'); ?>

<!-- JQUERY VALIDATE -->
<?php echo $this->Html->script('plugin/jquery-validate/jquery.validate.min'); ?>

<!-- JQUERY MASKED INPUT -->
<?php echo $this->Html->script('plugin/masked-input/jquery.maskedinput.min'); ?>

<!-- JQUERY SELECT2 INPUT -->
<?php echo $this->Html->script('plugin/select2/select2.min'); ?>

<!-- JQUERY UI + Bootstrap Slider -->
<?php echo $this->Html->script('plugin/bootstrap-slider/bootstrap-slider.min'); ?>

<!-- browser msie issue fix -->
<?php echo $this->Html->script('plugin/msie-fix/jquery.mb.browser.min'); ?>

<!-- FastClick: For mobile devices -->
<?php echo $this->Html->script('plugin/fastclick/fastclick.min'); ?>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- Demo purpose only - Cambia los themes-->
<?php // echo $this->Html->script('demo.min'); ONLY DEMO?>

<!-- MAIN APP JS FILE -->
<?php echo $this->Html->script('app.min'); //THE CORE?>


<script>
    $(document).ready(function() {
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        pageSetUp();
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