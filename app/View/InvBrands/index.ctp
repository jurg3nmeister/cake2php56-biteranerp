<?php /* (c)Bittion | Created: 12/10/2014 | Developer:reyro | View: InvBrands/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/InvBrands/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php // echo $this->element('SmartWidgetContentStart', array('icon' => $this->Html->link('<i class="fa fa-plus"></i> Productos', array('action' => 'save'), array('title' => 'Crear', 'class' => 'btn btn-primary', 'escape' => false)) . ' ')); ?>
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Marcas', 'icon' => '<i class="fa fa-table"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="InvBrandIndexDT" class="table table-striped table-bordered table-hover table-condensed" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th></th> <!-- BUTTONS -->
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

