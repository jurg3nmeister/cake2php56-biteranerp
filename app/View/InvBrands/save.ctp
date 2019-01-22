<?php /* (c)Bittion | Created: 12/10/2014 | Developer:reyro | View: InvBrands/save */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/InvBrands/save', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->

<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Marca', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('InvBrand'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>                            
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('name', 'col-3', array('label' => '*Nombre:', 'maxlength' => '160')); ?>    
        <?php echo $this->SmartForm->input('description', 'col-6', array('label' => 'Descripción:', 'maxlength' => '600')); ?>                            
    </div>
</fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Marcas', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>

<!-- ------------------ END CONTENT ------------------ -->

<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>