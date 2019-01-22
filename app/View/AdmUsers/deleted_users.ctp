<?php /* (c)Bittion Admin Module | Created: 01/09/2014 | Developer:reyro | View: AdmUsers/deleted_users */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmUsers/deleted_users', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Usuarios Eliminados', 'icon' => '<i class="fa fa-table"></i>')); 
echo $this->Html->link('Volver', array('action' => 'index'), array('title' => 'Volver', 'class' => 'btn btn-default', 'escape' => false));
?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="AdmUserDeletedUsersDT" class="table table-striped table-bordered table-hover" width="100%">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Nombre(s)</th>
            <th>Apellido(s)</th>
            <th>Rol</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->