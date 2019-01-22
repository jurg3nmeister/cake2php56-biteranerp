<?php /* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro | View: AdmPeriods/deleted_periods */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/datatables/jquery.dataTables.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.colVis.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.tableTools.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/datatables/dataTables.bootstrap.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmPeriods/deleted_periods', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Gestiones Eliminadas', 'icon' => '<i class="fa fa-table"></i>')); 
//echo $this->Html->link('Volver', array('action' => 'index'), array('title' => 'Volver', 'class' => 'btn btn-default', 'escape' => false));
?>
<!-- ------------------ START CONTENT ------------------ -->
<table id="AdmPeriodIndexDT" class="table table-striped table-bordered table-hover" width="100%">
    <thead>
        <tr>
            <th>Gestión</th>
            <th></th> <!-- BUTTONS -->
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

