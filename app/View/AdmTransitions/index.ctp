<?php /* (c)Bittion Admin Module | Created: 20/08/2014 | Developer:reyro | View: AdmTransitions/index | Description: All in one Transactions, States and Transitions */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/x-editable/moment.min', FALSE); ?> 
<?php echo $this->Html->script('plugin/x-editable/x-editable.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmTransitions/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Ciclos de Vida', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!----------------------------------------------- START CONTENT ----------------------------------->
<!--<div class="widget-body">-->
    <form class="smart-form">
        <fieldset>
            <section class="col col-6">
                <label class="label">Controlador:</label>
                <label class="select"> 
                    <?php echo $this->Form->input('selectController', array('class' => 'col-xs-12 col-sm-12 col-md-8 col-lg-8', 'label' => false, 'div' => false, 'type' => 'select', 'options' => $controllers)); ?>
                </label>
            </section>
        </fieldset>
    </form>
    <!--<hr class="simple">-->
    <ul id="myTab" class="nav nav-tabs bordered">
        <li class="active">
            <a href="#s1" data-toggle="tab">Transiciones</a>
        </li>
        <li>
            <a href="#s2" data-toggle="tab">Estados</a>
        </li>
        <li>
            <a href="#s3" data-toggle="tab">Transacciones</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade active in" id="s1">
            <!-- ------------------------------------------------------------ START TAB 1 ------------------------------------------------------------ -->
            <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
            <?php echo $this->element('SmartWidgetContentStart', array('icon' => '<button type="button" id="btnCreateTransition" class="btn btn-primary" title="Crear"><i class="fa fa-plus"></i></button>')); ?>
            <!-- ------------------ START CONTENT ------------------ -->
            <table id="AdmTransitionTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-md-3">Estado Inicial</th>
                        <th class="col-md-3">Transacción</th>
                        <th class="col-md-3">Estado Final</th>
                        <th class="col-md-3"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <!-- ------------------ END CONTENT ------------------ -->
            <?php echo $this->element('SmartWidgetContentEnd'); ?>
            <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
            <!-- ------------------------------------------------------------ END TAB 1 ------------------------------------------------------------ -->
        </div>
        <div class="tab-pane fade" id="s2">
            <!-- ------------------------------------------------------------ START TAB 2 ------------------------------------------------------------ -->
            <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
            <?php echo $this->element('SmartWidgetContentStart', array('icon' => '<button type="button" id="btnCreateState" class="btn btn-primary" title="Crear"><i class="fa fa-plus"></i></button>')); ?>
            <!-- ------------------ START CONTENT ------------------ -->
            <table id="AdmStateTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-md-4">Estado</th>
                        <th class="col-md-6">Descripción</th>
                        <th class="col-md-2"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <!-- ------------------ END CONTENT ------------------ -->
            <?php echo $this->element('SmartWidgetContentEnd'); ?>
            <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
            <!-- ------------------------------------------------------------ END TAB 2 ------------------------------------------------------------ -->
        </div>
        <div class="tab-pane fade" id="s3">
            <!-- ------------------------------------------------------------ START TAB 3 ------------------------------------------------------------ -->
            <!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
            <?php echo $this->element('SmartWidgetContentStart', array('icon' => '<button type="button" id="btnCreateTransaction" class="btn btn-primary" title="Crear"><i class="fa fa-plus"></i></button>')); ?>
            <!-- ------------------ START CONTENT ------------------ -->
            <table id="AdmTransactionTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-md-4">Transacción</th>
                        <th class="col-md-6">Descripción</th>
                        <th class="col-md-2"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <!-- ------------------ END CONTENT ------------------ -->
            <?php echo $this->element('SmartWidgetContentEnd'); ?>
            <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->
            <!-- ------------------------------------------------------------ END TAB 3 ------------------------------------------------------------ -->
        </div>
    </div>

<!--</div>-->
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->


<!-----------------------------------------------------------  MODALS --------------------------------------------------------->
<!-- Modal Transition -->
<div class="modal fade" id="modalTransition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel2">Crear Transición</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('AdmTransition'); ?>
                <div class="row">
                    <?php echo $this->SmartForm->select('adm_state_id', '', array('label' => 'Estado Inicial:', 'options' => $states, 'empty' => array('name' => 'Elija un estado', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                    <?php echo $this->SmartForm->select('adm_transaction_id', '', array('label' => 'Transacción:', 'options' => $transactions, 'empty' => array('name' => 'Elija una transacción', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                    <?php echo $this->SmartForm->select('adm_final_state_id', '', array('label' => 'Estado Final:', 'options' => $states, 'empty' => array('name' => 'Elija un estado', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo $this->SmartForm->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->SmartForm->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->SmartForm->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal State -->
<div class="modal fade" id="modalState" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel1">Crear Estado</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('AdmState'); ?>
                <div class="row">
                    <?php echo $this->SmartForm->input('name', '', array('label' => 'Nombre:', 'maxLength'=>'30')); ?>
                    <?php echo $this->SmartForm->textarea('description', '', array('label' => 'Descripción:', 'rows' => '2', 'maxLength'=>'60')); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo $this->SmartForm->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->SmartForm->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->SmartForm->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal Transaction -->
<div class="modal fade" id="modalTransaction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel3">Crear Transacción</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('AdmTransaction'); ?>
                <div class="row">
                    <?php echo $this->SmartForm->input('name', '', array('label' => 'Nombre:', 'maxLength'=>'15')); ?>
                    <?php echo $this->SmartForm->textarea('description', '', array('label' => 'Descripción:', 'rows' => '2', 'maxLength'=>'60')); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo $this->SmartForm->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->SmartForm->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->SmartForm->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
