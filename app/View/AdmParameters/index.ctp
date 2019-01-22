<?php /* (c)Bittion Admin Module | Created: 20/08/2014 | Developer:reyro | View: AdmParameters/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmParameters/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php // echo $this->element('SmartWidgetContentStart', array('icon' => $this->Html->link('<i class="fa fa-plus"></i> Parámetros', array('action' => 'create'), array('title' => 'Crear', 'class' => 'btn btn-primary', 'escape' => false)) . ' ')); ?>
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Parámetros', 'icon' => '<i class="fa fa-table"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="AdmParameterIndexDT" class="table table-striped table-bordered table-hover" width="100%">
    <thead>
        <tr>
            <th>Clave</th>
            <th>var_string_short</th>
            <th>var_string_long</th>
            <th>var_integer</th>
            <th>var_boolean</th>
            <th>var_decimal</th>
            <th></th> <!-- BUTTONS -->
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

