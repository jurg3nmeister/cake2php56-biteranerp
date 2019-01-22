<?php /* (c)Bittion Admin Module | Created: 12/08/2014 | Developer:reyro | View: AdmUsers/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmUsers/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Usuarios', 'icon' => '<i class="fa fa-table"></i>'));
//echo $this->Html->link('<i class="fa fa-plus"></i> Usuarios', array('action' => 'create'), array('title' => 'Crear', 'class' => 'btn btn-primary', 'escape' => false)) . ' ';
//echo $this->Html->link(' Restaurar', array('action' => 'deleted_users'), array('title' => 'Restaurar', 'class' => 'btn btn-success', 'escape' => false));
?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="AdmUserIndexDT" class="table table-striped table-bordered table-hover" width="100%">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Activo</th>
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