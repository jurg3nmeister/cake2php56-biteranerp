<?php /* (c)Bittion | Created: 21/02/2015 | Developer:reyro | View: SalInvoices/activation */ ?>
    <!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('modules/SalInvoices/activation', FALSE); ?>
    <!-- ------------------ END VIEW JS -------------------- -->
<?php // debug($this->request->data);?>
    <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Activar dosificación (Solo se usa una vez con el sistema de impuestos)', 'icon' => '<i class="fa fa-edit"></i>')); ?>
    <!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalInvoice'); ?>
<?php //echo $this->SmartForm->hidden('id'); ?><!--                            -->
    <fieldset>
        <div class="row">
            <?php echo $this->SmartForm->input('nit', 'col-3', array('label' => '*NIT Cliente:', 'maxlength' => '40')); ?>
            <?php echo $this->SmartForm->input('invoice_number', 'col-3', array('label' => '*Número Factura:', 'maxlength' => '10')); ?>
            <?php echo $this->SmartForm->input('date', 'col-3', array('label' => '*Fecha Factura:', 'maxlength' => '10', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
            <?php echo $this->SmartForm->input('total', 'col-3', array('label' => '*Monto Facturado:', 'maxlength' => '15')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->input('authorization_number', 'col-3', array('label' => '*Número de autorización:', 'maxlength' => '60')); ?>
            <?php echo $this->SmartForm->input('control_key', 'col-6', array('label' => '*LLave de control:', 'maxlength' => '100')); ?>
        </div>
    </fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
    <footer>
        <?php echo $this->Form->button('<i class="fa fa-gear"></i> Generar Código Control', array('class' => 'btn btn-primary', 'id' => 'btnGenerate')); ?>
        <?php echo $this->Form->button('<i class="fa fa-eraser"></i> Limpiar Campos', array('class' => 'btn btn-default', 'id' => 'btnClear')); ?>
    </footer>
<?php echo $this->SmartForm->end(); ?>

    <!-- ------------------ END CONTENT ------------------ -->

    <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>