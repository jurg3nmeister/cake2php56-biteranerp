<?php /* (c)Bittion Admin Module | Created: 12/08/2014 | Developer:reyro | View: AdmUsers/update */ ?>

<!-- ------------------ START VIEW JS ------------------ -->
<?php echo $this->Html->script('plugin/jquery-form/jquery-form.min', FALSE); ?>
<?php echo $this->Html->script('bittion/flashGrowlMessage', FALSE); ?>
<?php echo $this->Html->script('modules/AdmUsers/save', FALSE); ?>
<!-- ------------------ END VIEW JS -------------------- -->

<!-- ----------------------------------- START WIDGET CONTENT ----------------------------------- -->
<?php echo $this->element('SmartWidgetContentStart', array('title' => 'Usuario', 'icon' => '<i class="fa fa-edit"></i>')); ?>
<!-- ------------------ START CONTENT ------------------ -->
<ul id="myTab" class="nav nav-tabs bordered">
    <li class="active">
        <a href="#s1" data-toggle="tab">Cuenta</a>
    </li>
    <li>
        <a href="#s2" data-toggle="tab">Perfil</a>
    </li>
    <li>
        <a href="#s3" data-toggle="tab">Contraseña</a>
    </li>
</ul>

<?php echo $this->SmartForm->create('AdmUser'); ?>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="s1">
        <!---------------------------------------- START TAB1 CONTENT ---------------------------------------->

        <?php echo $this->SmartForm->hidden('id'); ?>
        <?php echo $this->SmartForm->hidden('emailHidden', array('id' => 'emailHidden', 'value' => $this->request->data['AdmProfile']['email'])); ?>
        <fieldset>
            <div class="row">
                <?php echo $this->SmartForm->input('AdmProfile.given_name', 'col-6', array('label' => '*Nombre(s):', 'iconPrepend' => 'fa-user', 'maxLength' => '60')); ?>
                <?php echo $this->SmartForm->input('AdmProfile.family_name', 'col-6', array('label' => '*Apellido(s):', 'iconPrepend' => 'fa-user', 'maxLength' => '60')); ?>
            </div>
            <div class="row">
                <?php echo $this->SmartForm->input('username', 'col-4', array('label' => '*Nombre de Usuario:', 'maxLength'=>'30', 'after' => '<a href="#" id="linkGenerateUsername">Generar usuario mediante sistema</a>', 'iconPrepend' => 'fa-user', 'placeholder' => 'Puede crearlo o generarlo por sistema')); ?>
                <?php echo $this->SmartForm->select('adm_role_id', 'col-4', array('label' => '*Rol de Usuario:', 'options' => $roles, 'empty' => array('name' => 'Elija un rol', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                <?php echo $this->SmartForm->input('AdmProfile.email', 'col-4', array('label' => '*Correo Electrónico:', 'iconPrepend' => 'fa-envelope-o', 'maxLength' => '60')); ?>
            </div>
        </fieldset>
<!--        --><?php //echo $this->element('SmartFormButtons', array('btnSave' => $this->Form->button('Guardar', array('id' => 'btnUpdateUserProfile', 'class' => 'btn btn-primary', 'type' => 'button')))); //default save and cancel ?>
<!--        --><?php //echo $this->SmartForm->end(); ?>
        <!----------------------------------------- END TAB1 CONTENT ----------------------------------------->
    </div>

    <div class="tab-pane fade in" id="s2">
        <!---------------------------------------- START TAB2 CONTENT ---------------------------------------->
<!--        --><?php //echo $this->SmartForm->create('AdmProfile'); ?>
<!--        --><?php //echo $this->SmartForm->hidden('id'); ?>
        <fieldset>
            <div class="row">
                <?php echo $this->SmartForm->select('AdmProfile.birthplace', 'col-4', array('label' => 'País de Nacimiento:', 'options' => $countries, 'empty' => array('name' => 'Elija un país', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                <?php echo $this->SmartForm->input('AdmProfile.birthdate', 'col-4', array('label' => 'Fecha de Nacimiento:', 'iconPrepend' => 'fa-calendar', 'data-mask' => '99/99/9999', 'data-mask-placeholder' => '-')); ?>
                <?php echo $this->SmartForm->radio('AdmProfile.sex', 'col-4', 'inline-group', array('label' => 'Sexo:', 'options' => $sex, 'value' => $this->request->data['AdmProfile']['sex'])); ?>
            </div>

            <div class="row">
                <?php echo $this->SmartForm->input('AdmProfile.job_title', 'col-4', array('label' => 'Cargo:', 'placeholder' => 'Ej: Gerente, Vendedor, etc.', 'maxLength' => '50')); ?>
                <?php echo $this->SmartForm->input('AdmProfile.phone', 'col-3', array('label' => 'Telefono:', 'iconPrepend' => 'fa-phone', 'maxLength' => '30')); ?>
                <?php echo $this->SmartForm->input('AdmProfile.cellphone', 'col-3', array('label' => 'Celular:', 'iconPrepend' => 'fa-phone', 'maxLength' => '30')); ?>
            </div>
        </fieldset>

        <fieldset>
            <div class="row">
                <?php echo $this->SmartForm->input('AdmProfile.di_number', 'col-4', array('label' => 'Número de Documento:', 'maxLength' => '30')); ?>
                <?php echo $this->SmartForm->radio('AdmProfile.di_type', 'col-8', 'inline-group', array('label' => 'Tipo de Documento:', 'options' => $di_type, 'value' => $this->request->data['AdmProfile']['di_type'])); ?>
            </div>
        </fieldset>

        <fieldset>
            <div class="row">
                <?php echo $this->SmartForm->select('AdmProfile.address_country', 'col-4', array('label' => 'País de Domicilio:', 'options' => $countries, 'empty' => array('name' => 'Elija un país', 'value' => '', 'disabled' => TRUE, 'selected' => TRUE))); ?>
                <?php echo $this->SmartForm->input('AdmProfile.address_city', 'col-4', array('label' => 'Ciudad de Domicilio:', 'maxLength' => '60')); ?>
            </div>
            <?php echo $this->SmartForm->textarea('AdmProfile.address', '', array('label' => 'Dirección de Domicilio:', 'rows' => '2', 'maxLength' => '160')); ?>
        </fieldset>
<!--        --><?php //echo $this->element('SmartFormButtons'); //default save and cancel ?>
<!--        --><?php //echo $this->SmartForm->end(); ?>
        <!----------------------------------------- END TAB2 CONTENT ----------------------------------------->
    </div>

    <div class="tab-pane fade in" id="s3">
        <!---------------------------------------- START TAB3 CONTENT ---------------------------------------->
        <br>

        <p style="padding-left: 20px;">El sistema cambiará la contraseña del usuario por otra nueva.
<!--            <button type="button" class="btn btn-primary" id="btnResetPassword">Resetear Contraseña</button>-->
            <a href="#" id="btnResetPassword">Resetear Contraseña</a>
        </p>
        <!----------------------------------------- END TAB3 CONTENT ----------------------------------------->
    </div>
</div>
<footer>
    <?php echo $this->Form->button('<i class="fa fa-edit"></i> Guardar', array('class' => 'btn btn-primary', 'id' => 'btnSave')); ?>
    <?php echo $this->Html->link('<i class="fa fa-plus"></i> Nuevo', array('action' => 'save'), array('class' => 'btn btn-success', 'escape' => false, 'id' => 'btnNew')); ?>
    <?php echo $this->Html->link('<i class="fa fa-table"></i> Ver Usuarios', array('action' => 'index'), array('class' => 'btn bg-color-blueDark txt-color-white', 'escape' => false, 'id' => 'btnView')); ?>
</footer>
<?php echo $this->SmartForm->end(); ?>

<!-- ------------------ END CONTENT ------------------ -->
<?php echo $this->element('SmartWidgetContentEnd'); ?>
<!-- ------------------------------------ END WIDGET CONTENT ------------------------------------ -->


