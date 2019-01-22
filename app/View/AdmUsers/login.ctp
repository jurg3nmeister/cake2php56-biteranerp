<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro | View: AdmUsers/login */ ?>
<div class="col-md-4 col-lg-4"></div><!--To center on big screens-->
<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
    <div class="well no-padding">
        <?php echo $this->SmartForm->create('AdmUser', array('class'=>'smart-form client-form')); ?>
        <header>
            Inicie sesión en su cuenta
        </header>
        <fieldset>
            <?php echo $this->SmartForm->input('username', '', array('label'=>'Usuario:','iconAppend'=>'fa-user','after'=>'<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Por favor ingrese su usuario</b>'))?>
            <?php echo $this->SmartForm->input('password', '', array('type'=>'password','label'=>'Contraseña:','iconAppend'=>'fa-lock','after'=>'<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Por favor ingrese su contraseña</b>'))?>
        </fieldset>
        <footer>
            <button type="submit" class="btn btn-primary">
                Inicie sesión
            </button>
        </footer>
        <?php $this->SmartForm->end();?>
    </div>
    <br>
    <?php echo $this->Session->flash();?>
</div>
