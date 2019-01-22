<?php /* (c)Bittion | Created: 29/06/2015 | Developer:reyro | View: SalSale/sale_report_generator */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('bittion/flashGrowlMessage', FALSE); ?>
<?php echo $this->Html->script('modules/SalSales/sale_report_generator', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->

<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Reporte de Ventas', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalSale', array(/*'target'=>'_blank', */'class'=>'smart-form')); ?>
<?php //echo $this->SmartForm->hidden('id'); ?><!--                            -->
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('startDate', 'col-2', array('label' => '* Fecha Inicio:', 'required', 'maxlength' => '10', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
        <?php echo $this->SmartForm->input('endDate', 'col-2', array('label' => '* Fecha Fin:', 'required', 'maxlength' => '10', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
    </div>
</fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-gear"></i> Generar Reporte', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>

<!-- ------------------ END CONTENT ------------------ -->

<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>