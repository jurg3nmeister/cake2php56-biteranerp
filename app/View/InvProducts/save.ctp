<?php /* (c)Bittion | Created: 18/09/2014 | Developer:reyro | View: InvProducts/update */ ?>
<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?> 
<?php echo $this->Html->script('modules/InvProducts/save', FALSE); ?>

<?php echo $this->Html->script('plugin/jcrop/jquery.Jcrop.min', FALSE); ?>
<?php echo $this->Html->script('plugin/jcrop/jquery.color.min', FALSE); ?>
<?php echo $this->Html->script('modules/InvProducts/script', FALSE); ?>
<?php echo $this->Html->css('jquery.Jcrop'); ?>
<!-- ------------------ END VIEW JS -------------------- -->

<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Producto', 'icon' => '<i class="fa fa-edit"></i>')); ?>

<!-- ----------------------------------- START TABS HEADER ----------------------------------- -->
<ul id="myTab" class="nav nav-tabs bordered">
    <li class="active">
        <a href="#s1" data-toggle="tab">Detalles</a>
    </li>
    <li>
        <a href="#s2" data-toggle="tab">Imagen</a>
    </li>
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
                <?php echo $this->SmartForm->select('inv_category_id', 'col-3', array('label' => '*Categorias:', 'options' => $categories, 'empty' => array('name' => '', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>   
                <?php echo $this->SmartForm->select('inv_brand_id', 'col-3', array('label' => '*Marcas:', 'options' => $brands, 'empty' => array('name' => '', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                <?php echo $this->SmartForm->input('supplier', 'col-4', array('label' => '*Proveedor:', 'maxlength' => '150')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->input('code', 'col-2', array('label' => '*Codigo:', 'maxlength' => '40')); ?>
                <?php echo $this->SmartForm->input('name', 'col-8', array('label' => '*Nombre:', 'maxlength' => '350')); ?>
<!--                --><?php //echo $this->SmartForm->inputAutocomplete('measure', 'col-2', array('id' => 'listMeasure', 'list' => $measures), array('label' => 'Medida:', 'maxlength' => '50')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->textarea('description', 'col-10', array('rows' => '10', 'label' => 'Descripción:', 'maxlength' => '3000')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->select('website', 'col-2', array('label' => '*Pagina web:', 'options' => $booleans)); ?>
                <?php echo $this->SmartForm->input('InvPrice.0.price', 'col-2', array('label' => '*Precio Unitario (Bs.):', 'maxlength' => '15',/* 'after' => '<a href="#" id="linkGenerateProductPrice">Generar Precio</a>'*/ )); ?>
                <?php echo $this->SmartForm->hidden('purchase_price'); ?>
                <?php echo $this->SmartForm->hidden('profit_percentage'); ?>
            </div>
        </fieldset>
        <footer>
            <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id'=>'btnSave')); ?>
            <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id'=>'btnNew')); ?>
            <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Productos', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id'=>'btnView')); ?>
        </footer>
        <?php echo $this->SmartForm->end(); ?>

        <!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->

        <!-- ------------------------------------------------------------ END TAB 1 ------------------------------------------------------------ -->
    </div>
    <div class="tab-pane fade" id="s2">
        <!-- ------------------------------------------------------------ START TAB 2 ------------------------------------------------------------ -->

        <!-- ------------------ START CONTENT ------------------ -->
        <?php //echo $this->Form->create('InvProduct',array('class'=>'form-horizontal',  /*'id'=>'upload_form' ,*/'onsubmit'=>'return checkForm()'/* 'action'=>'upload.php',*/ /*'enctype'=>'multipart/form-data'*/)) ?>

        <?php echo $this->SmartForm->create('InvProduct', array('id'=>'InvProductPictureForm',/* 'type'=>'post',*/ 'enctype'=>'multipart/form-data', 'onsubmit'=>'return checkForm()', 'class'=>'form-horizontal', 'novalidate'=>'novalidate', 'inputDefaults'=>array('wrapInput'=>false))); ?>
        <?php echo $this->SmartForm->hidden('id_2', array('value'=>$id_2)); ?>
        <fieldset class="padding-10">
            <!-- hidden crop params -->
            <input type="hidden" id="x1" name="x1" />
            <input type="hidden" id="y1" name="y1" />
            <input type="hidden" id="x2" name="x2" />
            <input type="hidden" id="y2" name="y2" />

            <!-- <legend>Default Form Elements</legend> -->
            <div class="form-group">
                <?php echo $this->SmartForm->inputFile('picture', 'col-12', array('class'=>'btn btn-default', 'type' => 'file', 'label' => 'Imagen:', 'onchange'=>'fileSelectHandler()')); //repair readonly display ?>
            </div>
            <div id="error">
                <?php //$this->Session->setFlash('Usuario inactivo', 'alert', array(/*'id' => 'error' ,*/'plugin' => 'BoostCake', 'class' => 'alert-danger')); ?>
            </div>
            <!-- <div class="form-group superbox-show" style="display: block;"> -->
            <div class="form-group">
                <?php //if(isset($image)){?>
                <?php //if($image != ''){?>
                <?php echo $this->Html->image('products/'.$image, array('id' => 'preview', 'alt' => 'Imagen del producto'/*, 'class' => 'superbox-current-img-bittion'/*, 'border' => '0', 'width' => '25'*/)); 	?>
                <?php //} }?>
            </div>
            <div class="info">
                <!-- <label>File size</label> --> <input type="hidden" id="filesize" name="filesize" />
                <!-- <label>Type</label> --> <input type="hidden" id="filetype" name="filetype" />
                <!-- <label>Image dimension</label> --> <input type="hidden" id="filedim" name="filedim" />
                <!-- <label>W</label> --> <input type="hidden" id="w" name="w" />
                <!-- <label>H</label> --> <input type="hidden" id="h" name="h" />
            </div>
        </fieldset>
        <?php // echo $this->element('SmartFormButtons', array('btnExtra1' => $this->Html->link(' Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false)))); //default save and cancel ?>
        <footer>
            <?php echo $this->Form->button('<i class="fa fa-upload"></i> Subir Imagen', array( 'class' => 'btn btn-primary', 'id'=>'btnUpload')); ?>
            <!--	<button class="btn btn-primary" id="btnUpload" type="submit">Subir</button>	-->
        </footer>
        <?php echo $this->SmartForm->end(); ?>
        <!-- ------------------------------------ END CONTENT ------------------------------------ -->

        <!-- ------------------------------------------------------------ END TAB 2 ------------------------------------------------------------ -->
    </div>
</div>

<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>



<!-- -------------------------------------------------- START MODAL ---------------------------------------------------->
<div class="modal fade" id="modalPriceProduct" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel2">Generar Precio</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->SmartForm->create('InvModalProductPrice'); ?>
                <?php echo $this->SmartForm->hidden('id'); ?>
                <div class="row">
                    <?php echo $this->SmartForm->input('purchase_price', 'col-6', array('label' => 'Precio Compra (Bs):', 'maxlength' => '20' /* , 'after' => '<div class="note"><strong>Moneda:</strong> <span id="spanCurrency">BOLIVIANOS (Bs.)</span></div>' */)); ?>
                    <?php echo $this->SmartForm->input('profit_percentage', 'col-6', array('label' => 'Utilidad Esperada (%):', 'maxlength' => '3' , 'after' => 'Presione "Enter" para generar')); ?>
                </div>
                <div class="row">
                    <div style="padding: 4px 15px;">Utilidad (Bs): <span id="modalProfit" style="font-weight: bold;"></span></div>
                </div>
                <div class="row">
                    <div style="padding: 8px 15px;">Compra + Utilidad (Bs): <span id="modalPurchaseProfit" style="font-weight: bold;"></span></div>
                </div>
                <div class="row">
                    <div style="padding: 8px 15px;">Impuesto IVA (Bs): <span id="modalIva" style="font-weight: bold;"></span></div>
                </div>
                <div class="row">
                    <div style="padding: 8px 15px;">Impuesto IT (Bs): <span id="modalIt" style="font-weight: bold;"></span></div>
                </div>
                <div class="row">
                    <div style="padding: 8px 15px;">Precio Venta Sugerido (Bs): <span id="modalSalePrice" style="font-weight: bold;"></span></div>
                </div>
                <div class="row" style="margin-top: 8px;">
                    <?php echo $this->SmartForm->input('sale_price', 'col-6', array('label' => 'Precio Venta Final (Bs):', 'maxlength' => '20' /* , 'after' => '<div class="note"><strong>Moneda:</strong> <span id="spanCurrency">BOLIVIANOS (Bs.)</span></div>' */)); ?>
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