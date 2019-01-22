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
    </head>       

    <body>
        <?php echo $this->fetch('content'); ?>
    </body>
</html>
