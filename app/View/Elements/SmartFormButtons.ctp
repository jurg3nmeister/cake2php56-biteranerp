<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<footer>
    <?php
    if(!isset($btnSave)){
        echo $this->Form->button('Guardar', array('class' => 'btn btn-primary', 'id'=>'btnSave'));
    }else{
        echo $btnSave;
    }
    if(isset($btnExtra1)){
        echo $btnExtra1;
    }
    if(isset($btnExtra2)){
        echo $btnExtra2;
    }
    if(isset($btnExtra3)){
        echo $btnExtra3;
    }
    if(!isset($btnCancel)){
        echo $this->Html->link(' Volver', array('action' => 'index'), array('class' => 'btn btn-default', 'escape' => false));
    }else{
        echo $btnCancel;
    }
    
    ?>
</footer>