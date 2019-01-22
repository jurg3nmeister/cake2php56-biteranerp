<?php /* (c)Bittion | Created: 18/09/2014 | Developer:reyro | View: InvProducts/update */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/InvProducts/save_service', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->

<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Servicio', 'icon' => '<i class="fa fa-edit"></i>')); ?>

<!-- ----------------------------------- START TABS HEADER ----------------------------------- -->
<ul id="myTab" class="nav nav-tabs bordered">
    <li class="active">
        <a href="#s1" data-toggle="tab">Detalles</a>
    </li>
<!--    <li>-->
<!--        <a href="#s2" data-toggle="tab">Imagen</a>-->
<!--    </li>-->
</ul>
<!-- ----------------------------------- END TABS HEADER ----------------------------------- -->

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="s1">
        <!-- ------------------------------------------------------------ START TAB 1 ------------------------------------------------------------ -->


        <!-- ------------------ START CONTENT ------------------ -->
        <?php echo $this->SmartForm->create('InvProduct'); ?>
        <?php echo $this->SmartForm->hidden('id'); ?>                            
        <fieldset>
            <div class="row">
<!--                --><?php //echo $this->SmartForm->select('inv_category_id', 'col-3', array('label' => '*Categorias:', 'options' => $categories, 'empty' => array('name' => '', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?><!--   -->
<!--                --><?php //echo $this->SmartForm->select('inv_brand_id', 'col-3', array('label' => '*Marcas:', 'options' => $brands, 'empty' => array('name' => '', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?><!--   -->
            </div>
            <div class="row">
                <?php echo $this->SmartForm->input('code', 'col-2', array('label' => '*Codigo:', 'maxlength' => '40')); ?>
                <?php echo $this->SmartForm->input('name', 'col-8', array('label' => '*Nombre:', 'maxlength' => '350')); ?>
<!--                --><?php //echo $this->SmartForm->inputAutocomplete('measure', 'col-2', array('id' => 'listMeasure', 'list' => $measures), array('label' => 'Medida:', 'maxlength' => '50')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->textarea('description', 'col-6', array('rows' => '2', 'label' => 'Descripción:', 'maxlength' => '3000')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->select('website', 'col-2', array('label' => '*Pagina web:', 'options' => $booleans)); ?>
<!--                --><?php //echo $this->SmartForm->select('service', 'col-2', array('label' => '*Servicio:', 'options' => $booleans)); ?>
                <?php echo $this->SmartForm->input('InvPrice.0.price', 'col-2', array('label' => '*Precio Unitario (Bs.):', 'maxlength' => '15')); ?>
            </div>
        </fieldset>
        <?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
        <footer>
            <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id'=>'btnSave')); ?>
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save_service'), array('class' => 'btn btn-success', 'escape' => false, 'id'=>'btnNew')); ?>
            <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Servicios', array('action' => 'services'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id'=>'btnView')); ?>
        </footer>
        <?php echo $this->SmartForm->end(); ?>

        <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

        <!-- ------------------------------------------------------------ END TAB 1 ------------------------------------------------------------ -->
    </div>
<!--    <div class="tab-pane fade" id="s2">-->
        <!-- ------------------------------------------------------------ START TAB 2 ------------------------------------------------------------ -->
        <!-- ------------------------------------------------------------ END TAB 2 ------------------------------------------------------------ -->
<!--    </div>-->
</div>

<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>