<?php /* (c)Bittion Admin Module | Created: 01/09/2014 | Developer:reyro | View: AdmUsers/change_email */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmUsers/change_email', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Cambiar Correo Electrónico <span id="spaEmail" class="badge bg-color-darken">'.$email.'</span>', 'icon'=>'<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('AdmUser'); ?>
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('password', 'col-4', array('label' => 'Ingrese su contraseña:', 'type'=>'password','iconPrepend' => 'fa-lock', 'maxLength'=>'25')); ?>
    </div>
    <div class="row">
        <?php echo $this->SmartForm->input('email', 'col-4', array('label' => 'Nuevo Correo Electrónico:', 'iconPrepend' => 'fa-envelope', 'maxLength'=>'60')); ?>
    </div>
</fieldset>
<?php echo $this->element('SmartFormButtons'); //default save and cancel ?>
<?php echo $this->SmartForm->end(); ?>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->    