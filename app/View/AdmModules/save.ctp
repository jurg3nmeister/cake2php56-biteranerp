<?php /* (c)Bittion Admin Module | Created: 07/01/2015 | Developer:reyro | View: AdmModules/save */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmModules/save', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Rol', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('AdmModule'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('name', 'col-4', array('label' => '*Nombre:', 'maxLength'=>'15')); ?>
        <?php echo $this->SmartForm->input('initials', 'col-2', array('label' => '*Sigla:', 'maxLength'=>'3')); ?>
        <?php echo $this->SmartForm->input('description', 'col-6', array('label' => '*Descripción:', 'maxLength'=>'60')); ?>
    </div>
</fieldset>

<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Módulos', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

