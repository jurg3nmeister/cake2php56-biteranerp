<?php /* (c)Bittion | Created: 17/10/2014 | Developer:reyro | View: SalInvoices/save */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/SalInvoices/save', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<?php // debug($this->request->data);?>
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Dosificación', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalInvoice'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>                            
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('tax_number', 'col-3', array('label' => '*NIT:', 'maxlength' => '30')); ?>
        <?php echo $this->SmartForm->input('tax_name', 'col-4', array('label' => '*Nombre/Razon Social:', 'maxlength' => '160')); ?>
        <?php echo $this->SmartForm->input('legal_representative', 'col-4', array('label' => '*Representante:', 'maxlength' => '200')); ?>

    </div>
    <div class="row">
        <?php echo $this->SmartForm->input('main_activity', 'col-4', array('label' => '*Actividad:', 'maxlength' => '120')); ?>
        <?php echo $this->SmartForm->input('description', 'col-6', array('label' => 'Descripción:', 'maxlength' => '300')); ?>
    </div>
    <div class="row">
        <?php echo $this->SmartForm->input('authorization_number', 'col-3', array('label' => '*Número de autorización:', 'maxlength' => '60')); ?>
        <?php echo $this->SmartForm->input('control_key', 'col-6', array('label' => '*LLave de control:', 'maxlength' => '100')); ?>
    </div>
    <div class="row">
        <?php echo $this->SmartForm->input('start_date', 'col-2', array('label' => '*Fecha de inicio:', 'maxlength' => '10', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
        <?php echo $this->SmartForm->input('valid_days', 'col-2', array('label' => '*Días de validez:', 'maxlength' => '3')); ?>
        <?php echo $this->SmartForm->select('active', 'col-2', array('label' => '*Activo:', 'options' => $booleans, 'empty' => array('name' => '', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
    </div>
</fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Dosificaciones', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>

<!-- ------------------ END CONTENT ------------------ -->

<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>