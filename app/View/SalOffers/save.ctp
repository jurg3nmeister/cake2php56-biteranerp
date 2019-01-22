<?php /* (c)Bittion | Created: 23/09/2014 | Developer:reyro | View: SalOffers/save | Description: create and update (customers and their employees */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/SalOffers/save', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Cotización<span id="spanOfferTitle">' . $systemCode . '</span>', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<?php echo $this->SmartForm->create('SalOffer'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>                            
<fieldset>
    <div class="row">
        <?php echo $this->SmartForm->input('date', 'col-3', array('label' => '* Fecha:', 'value' => $date, 'maxlength' => '10', 'iconPrepend' => 'fa-calendar', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>    
    </div>
    <div class="row">
        <?php echo $this->SmartForm->select('sal_customer_id', 'col-5', array('label' => '* Cliente:', 'select2' => 'select2', 'options' => $customers, 'style'=>'width:100%')); ?>
        <?php echo $this->SmartForm->inputAutocomplete('person_requesting', 'col-3', array('id' => 'listCustomersEmployee', 'list' => array()), array('label' => 'Persona de referencia:', 'placeholder' => 'Nombre y Apellido', 'maxlength' => '160')); ?>                            
    </div>
    <?php echo $this->SmartForm->textarea('note', '', array('rows' => '2', 'label' => 'Nota:', 'maxlength' => '600')); ?>
</fieldset>
<?php // echo $this->element('SmartFormButtons'); //default save and cancel ?>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Cotizaciones', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>

<?php echo $this->Form->button('<i class="fa fa-plus"></i> Productos', array('class' => 'btn btn-success btn-xs', 'id' => 'btnModalCreateProduct', 'type' => 'button', 'title' => 'Nuevo')); ?>
<table id="SalOffersDetailIndexDT" class="table table-striped table-bordered table-hover table-condensed" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Codido de Producto</th>
            <th>Descripción</th>
            <th>Medida</th>
            <th>Precio (Bs.)</th>
            <th>Cantidad (Uni.)</th>
            <th>Subtotal (Bs.)</th>
            <th></th> <!-- BUTTONS -->
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="6" style="font-weight: bold;text-align: right;padding-right: 20px;">Total</td>
            <td colspan="2" style="font-weight: bold;"><span id="spanTotal">0.00</span></td>
        </tr>
    </tfoot>
    <tbody>

    </tbody>
</table>
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->




<!-- -------------------------------------------------- START MODAL ---------------------------------------------------->
<div class="modal fade" id="modalProduct" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel2">Producto </h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('SalOffersDetail'); ?>
                <?php echo $this->SmartForm->hidden('id'); ?>
                <div class="row">
                    <section id="sectionUpdateOfferDetail" style="padding-right: 15px; padding-left: 15px;font-weight: bold"></section>
                    <?php echo $this->SmartForm->select('inv_product_id', 'col-xs-12 col-sm-12 col-md-12 col-lg-12', array('label' => '* Producto:', 'empty' => array('name' => 'Elija un producto', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE), 'select2' => 'select2', 'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12')); ?>   
                </div>
                <div class="row">
                    <?php echo $this->SmartForm->hidden('price'); ?>
                    <?php echo $this->SmartForm->input('sale_price', 'col-6', array('label' => 'Precio (Bs.):', 'maxlength' => '20')); ?>         
                    <?php echo $this->SmartForm->input('quantity', 'col-6', array('label' => 'Cantidad (Uni.):', 'maxlength' => '12')); ?>   
                </div>
                <div class="row">

                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->SmartForm->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->SmartForm->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->SmartForm->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- -------------------------------------------------- END MODAL ---------------------------------------------------->