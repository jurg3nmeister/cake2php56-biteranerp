<?php /* (c)Bittion | Created: 07/06/2015 | Developer:reyro | View: SalProposals/save */ ?>
    <!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('modules/SalProposals/save', FALSE); ?>
    <!-- ------------------ END VIEW JS -------------------- -->

    <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Propuesta', 'icon' => '<i class="fa fa-edit"></i>')); ?>
    <!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalProposal'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>
    <fieldset>
        <div class="row">
            <?php echo $this->SmartForm->input('name', 'col-6', array('label' => '*Nombre:', 'maxlength' => '160')); ?>
            <?php echo $this->SmartForm->input('sent_date', 'col-2', array('label' => '*Fecha de envio:', 'maxlength' => '10', 'iconPrepend' => 'fa-calendar', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->textarea('description', 'col-10', array('rows' => '2', 'label' => '*Descripción:', 'maxlength' => '6000')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->select('sal_customer_id', 'col-5', array('label' => '* Cliente:', 'select2' => 'select2', 'options' => $customers, 'style'=>'width:100%')); ?>
            <?php echo $this->SmartForm->select('type', 'col-2', array('label' => '* Tipo:', 'options' => $types, 'style'=>'width:100%')); ?>
            <?php echo $this->SmartForm->select('lc_state', 'col-2', array('label' => '* Estado:', 'options' => $lc_states, 'style'=>'width:100%')); ?>
        </div>
        <div class="row">
            <?php echo $this->SmartForm->textarea('note', 'col-6', array('rows' => '2', 'label' => 'Nota:', 'maxlength' => '6000')); ?>
            <?php echo $this->SmartForm->input('price', 'col-2', array('label' => '*Precio:', 'maxlength' => '15')); ?>
        </div>
    </fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
    <footer>
        <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
        <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
        <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Propuestas', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
    </footer>
<?php echo $this->SmartForm->end(); ?>

    <!-- ------------------ END CONTENT ------------------ -->

    <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>