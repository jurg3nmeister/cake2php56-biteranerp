<?php /* (c)Bittion | Created: 17/10/2014 | Developer:reyro | View: SalInvoices/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/SalInvoices/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php // echo $this->element('SmartWidgetContentStart', array('icon' => $this->Html->link('<i class="fa fa-plus"></i> Productos', array('action' => 'save'), array('title' => 'Crear', 'class' => 'btn btn-primary', 'escape' => false)) . ' ')); ?>
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Dosificaciones', 'icon' => '<i class="fa fa-table"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="SalInvoiceIndexDT" class="table table-striped table-bordered table-hover table-condensed" width="100%">
    <thead>
        <tr>
            <th>NIT</th>
            <th>Nombre</th>
            <th>Número de autorización</th>
<!--            <th>Llave de control</th>-->
            <th>Fecha de expiración</th>
            <th>Activo</th>
            <th></th> <!-- BUTTONS -->
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

