<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white">Bittion © <?php echo date('Y');?></span>
        </div>
        <div class="col-xs-6 col-sm-6 text-right hidden-xs">
            <div class="txt-color-white inline-block">
                <?php if ($this->Session->check('Auth.User.AdmLogin.day')){?>
                <i class="txt-color-blueLight hidden-mobile">Último ingreso a la cuenta <i class="fa fa-clock-o"></i> 
                    <?php $months = array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo', '06'=>'Junio', '07'=>'Julio', '08'=>'Agosto', '09'=>'Septiembre', '10'=>'Octubre', '11'=>'Noviembre', '12'=>'Diciembre');?>
                    <strong>
                        <!--Lunes 28 de Agosto de 2014, a horas: 15:03-->
                        <?php echo $this->Session->read('Auth.User.AdmLogin.day');?> de 
                        <?php echo $months[$this->Session->read('Auth.User.AdmLogin.month')];?> de 
                        <?php echo $this->Session->read('Auth.User.AdmLogin.year');?>, a horas:
                        <?php echo $this->Session->read('Auth.User.AdmLogin.hour');?> :
                        <?php echo $this->Session->read('Auth.User.AdmLogin.min'); ?>
                    </strong> 
                </i>
                <?php }?>
            </div>
        </div>
    </div>
</div>