<?php /* (c)Bittion Admin Module | Created: 07/01/2015 | Developer:reyro | View: AdmParameters/save */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmParameters/save', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Rol', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('AdmParameter'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('parameter_key', 'col-4', array('label' => 'Parámetro Clave:')); ?>
    </div>

    <div class="row">
        <?php echo $this->SmartForm->input('var_string_short', 'col-2', array('label' => 'var_string_short:', 'maxlength' => '10')); ?>
        <?php echo $this->SmartForm->input('var_string_long', 'col-2', array('label' => 'var_string_long:', 'maxlength' => '80')); ?>
        <?php echo $this->SmartForm->input('var_integer', 'col-2', array('label' => 'var_integer:', 'maxlength' => '10')); ?>
        <?php echo $this->SmartForm->select('var_boolean', 'col-2', array('label' => 'var_boolean:', 'options' => array(0 => 'false', 1 => 'true'), 'empty' => array('name' => 'Ninguno', 'value' => '', 'selected' => TRUE))); ?>
        <?php echo $this->SmartForm->input('var_decimal', 'col-2', array('label' => 'var_decimal:', 'maxlength' => '15')); ?>
    </div>
</fieldset>

<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Parámetros', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

