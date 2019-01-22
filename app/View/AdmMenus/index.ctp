<?php /* (c)Bittion Admin Module | Created: 22/08/2014 | Developer:reyro | View: AdmMenus/index */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/AdmMenus/index', FALSE); ?> 
<!-- ------------------ END VIEW JS -------------------- -->
<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Crear Menu (los usuarios deben iniciar sesión para obtener los cambios)', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<div class="tree smart-form" id="treeMenu"> </div>
<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->


<!-- Modal Transaction -->
<div class="modal fade" id="modalSave" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel1">Crear Menu</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('AdmMenu'); ?>
                <?php echo $this->SmartForm->hidden('id'); ?>
                <div class="row">
                    <?php echo $this->SmartForm->input('name', 'col-8', array('label' => 'Nombre:', 'maxlength' => '40')); ?>
                    <?php echo $this->SmartForm->input('order_menu', 'col-4', array('label' => 'Orden:', 'maxlength' => '2', 'default'=>0)); ?>
                </div>

                <div class="row">
                    <section class="col col-6">
                        <label class="label">Icono:</label>
                        <?php
                        echo $this->Form->input('icon', array('class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12', 'label' => false, 'div' => false, 'type' => 'select'
                            , 'empty' => array('name' => 'Ninguno', 'value' => '', 'selected' => TRUE)
                            , 'options' => array()
                        ));
                        ?>
                    </section>
                    <section class="col col-6">
                        <label class="label">Acción:</label>
                        <?php
                        echo $this->Form->input('adm_action_id', array('class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12', 'label' => false, 'div' => false, 'type' => 'select'
                            , 'empty' => array('name' => 'Ninguna', 'value' => '', 'selected' => TRUE)
                            , 'options' => array('Administracion' => array('AdmUser create', 'AdmUser index'))));
                        ?>
                    </section>
                </div>

                <div class="row" id="selectParent">
                    <section class="col col-10">
                        <label class="label">Menu Padre:</label>
                        <?php echo $this->Form->input('parent', array('class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12', 'label' => false, 'div' => false, 'type' => 'select')); ?>
                    </section>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->Form->button('Guardar', array('class' => 'btn btn-primary')); ?>
                <?php echo $this->Form->button('Cancelar', array('type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
            </div>
            <?php echo $this->form->end(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
