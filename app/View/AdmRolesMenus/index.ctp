<?php /* (c)Bittion Admin Module | Created: 24/08/2014 | Developer:reyro | View: AdmRolesMenus/index */ ?>
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmRolesMenus/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Roles Menus', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('AdmRolesMenu'); ?>
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->select('adm_role_id', 'col-6', array('label' => 'Rol:', 'options' => $roles, 'empty' => array('name' => 'Elija un rol', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
    </div>
    <!-- ------------------ START CHECKBOXES ------------------ -->
    <div class="tree smart-form" id="treeMenu"></div>
    <!-- ------------------ END CHECKBOXES ------------------ -->
</fieldset>
<?php echo $this->element('SmartFormButtons', array('btnCancel'=>'', 'btnSave' => $this->Form->button('Guardar', array('class' => 'btn btn-primary', 'disabled', 'id' => "btnSave", 'data-loading-text' => 'Procesando...')))); //default cancel ?>
<?php echo $this->SmartForm->end(); ?>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

