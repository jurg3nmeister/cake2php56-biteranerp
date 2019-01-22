<?php /* (c)Bittion | Created: 24/02/2015 | Developer:reyro | View: SalSales/admin_save | Description: create and update without restrictions */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?>
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?>
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?>
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?>
<?php echo $this->Html->script('modules/SalSales/admin_save', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Venta<span id="spanSaleTitle">' . $systemCode . '</span>', 'icon' => '<i class="fa fa-edit"></i>', 'widgetToolbar' => '<span class="label txt-color-white" id="spanDocumentState"></span>')); ?>
<!-- ------------------ START CONTENT ------------------ -->

<?php echo $this->SmartForm->create('SalSale'); ?>
<?php echo $this->SmartForm->hidden('id'); ?>
<?php echo $this->SmartForm->hidden('lc_state'); ?>

<fieldset>
    <div class="row">
                <?php echo $this->SmartForm->select('sal_office_id', 'col-5', array('label' => '* Sucursal:', 'options' => $offices)); ?>

    </div>

    <div class="row">
        <?php echo $this->SmartForm->input('date', 'col-2', array('label' => '* Fecha:', 'value' => $date, 'maxlength' => '10', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
        <?php echo $this->SmartForm->select('sal_customer_id', 'col-5', array('label' => '* Cliente:', 'options' => $customers, 'select2'=>'select2','style'=>'width:100%')); ?>
<!--    </div>-->
<!--    <div class="row">-->
        <section class="col col-2">
            <label class="label">NIT:</label>
            <label><h6><span id="spanCustomerNit"><?php echo $customerNit; ?></span></h6></label>
        </section>
        <section class="col col-2">
            <label class="label">Razon Social/Nombre:</label>
            <label><h6><span id="spanCustomerNitName"><?php echo $customerNitName; ?></span></h6></label>
        </section>
    </div>
    <div class="row">
        <?php echo $this->SmartForm->inputAutocomplete('person_requesting', 'col-3', array('id' => 'listCustomersEmployee', 'list' => array()), array('label' => 'Persona de referencia:', 'placeholder' => 'Nombre y Apellido', 'maxlength' => '160')); ?>
        <?php echo $this->SmartForm->input('discount', 'col-2', array('label' => 'Descuento (%):')); ?>
        <?php echo $this->SmartForm->input('payment_deadline', 'col-2', array('label' => 'Plazo (dias):'/*, 'disabled' => 'disabled'*/)); ?>
        <?php echo $this->SmartForm->input('paid', 'col-2', array('label' => 'Pagado (Bs.):'/*, 'disabled' => 'disabled'*/)); ?>
        <section class="col col-2">
            <label class="label">Debe (Bs.):</label>
            <label><h6><span id="spanDebt"><?php echo $debt; ?></span></h6></label>
        </section>
    </div>
    <div class="row">
        <?php echo $this->SmartForm->textarea('note', 'col-5', array('rows' => '2', 'label' => 'Nota:', 'maxlength' => '600')); ?>
        <section class="col col-2">
            <label class="label">Factura N°:</label>
            <label><h6><span id="spanInvoiceNumber"><?php echo $invoiceNumber; ?></span></h6></label>
        </section>
        <section class="col col-2">
            <label class="label">Autorización N°:</label>
            <label><h6><span id="spanAuthorizationNumber"><?php echo $authorizationNumber; ?></span></h6></label>
        </section>
        <section class="col col-2">
            <label class="label">Codigo de Control:</label>
            <label><h6><span id="spanControlCode"><?php echo $controlCode; ?></span></h6></label>
        </section>
    </div>
    <div class="row" id="divGenerateInvoice">
        <section class="col col-6">
            <button class="btn btn-success btn-xs" id="btnGenerateInvoice" type="button" title="Nuevo"><i class="fa fa-file-text-o"></i> Facturar</button>
<!--        Factura:-->
<!--        <span class="onoffswitch">-->
<!--            <input type="checkbox" class="onoffswitch-checkbox" id="autoopen-1" --><?php //if($switchInvoice == 1){echo 'checked="checked"';}?><!-- >-->
<!--            <label class="onoffswitch-label" for="autoopen-1">-->
<!--                <span class="onoffswitch-inner" data-swchon-text="SI" data-swchoff-text="NO"></span>-->
<!--                <span class="onoffswitch-switch"></span>-->
<!--            </label>-->
<!--        </span>-->
<!--            <span style="font-weight: bold; font-size: 12px; color: #ff0000; padding-left: 4px;">(Precaución! No se puede deshacer una vez facturado)</span>-->
        </section>
    </div>
</fieldset>
<?php // echo $this->element('SmartFormButtons', array('btnExtra1'=>$this->Form->button('Vender', array('class' => 'btn btn-success', 'id'=>'btnApprove', 'type'=>'button')))); //default save and cancel ?>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-times"></i> Anular', array('class' => 'btn btn-danger', 'id' => 'btnCancel', 'type' => 'button')); ?>
    <?php echo $this->Form->button('<i class="fa fa-trash-o"></i> Eliminar', array('class' => 'btn btn-danger', 'id' => 'btnDelete', 'type' => 'button')); ?>
    <?php echo $this->Form->button('<i class="fa fa-check"></i> Aprobar', array('class' => 'btn bg-color-green txt-color-white', 'id' => 'btnApprove', 'type' => 'button')); ?>
    <?php echo $this->Form->button('<i class="fa fa-file-text-o"></i> Ver Factura', array('class' => 'btn btn-info', 'id' => 'btnInvoice', 'type' => 'button')); ?>

    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'admin_save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Ventas', array('action' => 'admin_index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>
<?php echo $this->Form->button('<i class="fa fa-plus"></i> Productos / Servicios', array('class' => 'btn btn-success btn-xs', 'id' => 'btnModalCreateProduct', 'type' => 'button', 'title' => 'Nuevo')); ?>
<table id="SalSalesDetailIndexDT" class="table table-striped table-bordered table-hover table-condensed" width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Codigo de Producto</th>
        <th>Nombre</th>
        <th>Precio (Bs.)</th>
        <th>Cantidad (Uni.)</th>
        <th>Subtotal (Bs.)</th>
        <th></th>
        <!-- BUTTONS -->
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align: right;padding-right: 20px;">Total</td>
        <td colspan="1" style="font-weight: bold;"><span id="spanTotal">0.00</span></td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align: right;padding-right: 20px;">Descuento</td>
        <td colspan="1" style="font-weight: bold;"><span id="spanDiscount">0.00</span></td>
    </tr>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align: right;padding-right: 20px;">Total Neto</td>
        <td colspan="1" style="font-weight: bold;"><span id="spanTotalFinal">0.00</span></td>
    </tr>
    </tfoot>
    <tbody>

    </tbody>
</table>

<!-- ------------------ END CONTENT ------------------ -->
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
                <h4 class="modal-title" id="myModalLabel2">Producto / Servicio </h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('SalSalesDetail'); ?>
                <?php echo $this->SmartForm->hidden('id'); ?>
                <div class="row">
                    <section id="sectionUpdateSaleDetail"
                             style="padding-right: 15px; padding-left: 15px;font-weight: bold"></section>
                    <?php echo $this->SmartForm->select('inv_product_id', 'col-xs-12 col-sm-12 col-md-12 col-lg-12', array('label' => '* Producto / Servicio:', 'empty' => array('name' => 'Elija un producto', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE), 'select2' => 'select2', 'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12')); ?>
                </div>
                <div class="row">
                    <?php echo $this->SmartForm->textarea('invoice_alternative_name', 'col-xs-12 col-sm-12 col-md-12 col-lg-12', array('rows' => '2', 'label' => 'Nombre alternativo para factura:', 'maxlength' => '350')); ?>
                </div>
                <div class="row">
                    <?php echo $this->SmartForm->hidden('price'); ?>
                    <?php echo $this->SmartForm->input('sale_price', 'col-6', array('label' => 'Precio (Bs.):', 'maxlength' => '20' /* , 'after' => '<div class="note"><strong>Moneda:</strong> <span id="spanCurrency">BOLIVIANOS (Bs.)</span></div>' */)); ?>
                    <?php echo $this->SmartForm->input('quantity', 'col-6', array('label' => 'Cantidad (Uni.):', 'maxlength' => '12' /* , 'after' => '<div class="note"><strong>Medida:</strong> <span id="spanMeasure"></span></div>' */)); ?>
                </div>
                <div class="row">

                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->SmartForm->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->SmartForm->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->SmartForm->end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- -------------------------------------------------- END MODAL ---------------------------------------------------->