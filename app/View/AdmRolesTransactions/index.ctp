<?php /* (c)Bittion Admin Module | Created: 21/08/2014 | Developer:reyro | View: AdmRolesTransactions/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmRolesTransactions/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Roles Transacciones', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('AdmRolesTransaction'); ?>
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->select('AdmModule.id', 'col-6', array('label' => 'Módulo:', 'options' => $modules, 'empty' => array('name' => 'Elija un módulo', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
        <?php echo $this->SmartForm->select('adm_role_id', 'col-6', array('label' => 'Rol:', 'options' => $roles, 'empty' => array('name' => 'Elija un rol', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
    </div>
    <!-- ------------------ START CHECKBOXES ------------------ -->
    <div class="table-responsive" id="tblCheckboxesContainer"></div>
    <!-- ------------------ END CHECKBOXES ------------------ -->
</fieldset>
<?php echo $this->element('SmartFormButtons', array('btnCancel'=>'', 'btnSave' => $this->Form->button('Guardar', array('class' => 'btn btn-primary', 'disabled', 'id' => "btnSave", 'data-loading-text' => 'Procesando...')))); //default cancel ?>
<?php echo $this->SmartForm->end(); ?>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

