<?php /* (c)Bittion | Created: 15/01/2015 | Developer:reyro | View: SalOffices/save */ ?>
    <!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('modules/SalOffices/save', FALSE); ?>
    <!-- ------------------ END VIEW JS -------------------- -->

    <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Sucursal', 'icon' => '<i class="fa fa-edit"></i>')); ?>
    <!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalOffice'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>
    <fieldset>
        <div class="row">
            <?php echo $this->SmartForm->input('name', 'col-4', array('label' => '*Nombre detallado:', 'placeholder'=>'Oficina Central - San Miguel', 'maxlength' => '160')); ?>
            <?php echo $this->SmartForm->input('invoice_name', 'col-4', array('label' => '*Nombre corto:', 'placeholder'=>'Casa Matriz - 0', 'maxlength' => '160')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->input('address', 'col-8', array('label' => '*Dirección:', 'maxlength' => '600')); ?>
            <?php echo $this->SmartForm->input('phone', 'col-4', array('label' => 'Telefono(s):', 'maxlength' => '60')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->input('city', 'col-4', array('label' => '*Ciudad:', 'maxlength' => '150')); ?>
            <?php echo $this->SmartForm->input('country', 'col-4', array('label' => '*País:', 'maxlength' => '100')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->input('website', 'col-4', array('label' => 'Página Web:', 'maxlength' => '80')); ?>
        </div>
    </fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
    <footer>
        <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
        <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Sucursales', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
    </footer>
<?php echo $this->SmartForm->end(); ?>

    <!-- ------------------ END CONTENT ------------------ -->

    <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>