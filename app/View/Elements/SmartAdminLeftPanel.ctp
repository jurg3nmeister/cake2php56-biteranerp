<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it --> 
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <?php 
                if($this->Session->read('Auth.User.AdmProfile.photo') == null){
                    $urlAvatar = 'avatars/male.png';
                    if($this->Session->read('Auth.User.AdmProfile.sex') == 'F'){
                        $urlAvatar = 'avatars/female.png';
                    }
                }//else when the profile photo upload will be done
                echo $this->Html->image($urlAvatar, array("alt" => "me", "class" => "online")); 
                ?>
                <span>
                    <!--<i class="fa fa-lg fa-user"></i>-->
                    <?php
                    echo $this->Session->read('Auth.User.AdmProfile.given_name') .' '. $this->Session->read('Auth.User.AdmProfile.family_name');
                    ?>
                </span>
                <i class="fa fa-lg fa-angle-down"></i>
            </a> 
        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive

    To make this navigation dynamic please make sure to link the node
    (the reference to the nav > ul) after page load. Or the navigation
    will not initialize.
    -->
    <nav>
        <?php
        echo $this->Session->read('TreeMenu')
        ?>

    </nav>
    <span class="minifyme" data-action="minifyMenu"> 
        <i class="fa fa-arrow-circle-left hit"></i> 
    </span>

</aside>

<?php //SHORTCUT = Metro style options when clicked on user's name link' above the menu. Obs: When menu is on top this code is generated in other part, check the elements?>
<div id="shortcut">
    <ul>
        <li>
            <a href="<?php echo $this->webroot; ?>adm_users/change_email" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Correo </span> </span> </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>adm_users/change_password" class="jarvismetro-tile big-cubes bg-color-orange"> <span class="iconbox"> <i class="fa fa-key fa-4x"></i> <span>Contraseña</span> </span> </a>
        </li>
<!--        <li>
            <a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-red"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Gestiones</span> </span> </a>
        </li>-->
<!--        <li>
            <a href="javascript:void(0);" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>Perfil</span> </span> </a>
        </li>-->
    </ul>
</div>