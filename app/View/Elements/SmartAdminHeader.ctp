<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<header id="header">
    <script>
        document.write('<div id="loading-page" style="position:fixed; background-color: #eaeaea; height:100%; width:100%; z-index:10; top:0; left:0; text-align: center;vertical-align: middle;"><span style="top:50%;position: relative;transform: translateY(-50%); font-size: 24px;"><i class="fa fa-gear fa-4x fa-spin"></i><br>Cargando....</span></div>');
    </script>
    <!--START LOGO GROUP-->
    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo">
            <?php
            $logo = $this->Html->image('logo.png', array('alt' => 'biteranERP'));
            echo $this->Html->link($logo, array('controller'=>'adm_users','action' => 'home'), array('escape' => false));
            ?>
        </span>
        <!-- END LOGO PLACEHOLDER -->

        <!-- Note: The activity badge color changes when clicked and resets the number to 0
Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
        <!--<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b></span>-->

        <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
        <!--        <div class="ajax-dropdown">
        
                     the ID links are fetched via AJAX to the ajax container "ajax-notifications" 
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/mail.html">
                            Msgs (14) </label>
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/notifications.html">
                            notify (3) </label>
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="ajax/notify/tasks.html">
                            Tasks (4) </label>
                    </div>
        
                     notification content 
                    <div class="ajax-notifications custom-scroll">
        
                        <div class="alert alert-transparent">
                            <h4>Click a button to show messages here</h4>
                            This blank page message helps protect your privacy, or you can show the first message here automatically.
                        </div>
        
                        <i class="fa fa-lock fa-4x fa-border"></i>
        
                    </div>
                     end notification content 
        
                     footer: refresh area 
                    <span> Last updated on: 12/12/2013 9:43AM
                        <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
                            <i class="fa fa-refresh"></i>
                        </button> 
                    </span>
                     end footer 
        
                </div>-->
        <!-- END AJAX-DROPDOWN -->

    </div>
    <!--END LOGO GROUP-->

    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Comprimir menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- #MOBILE -->
        <!-- Top menu profile link : this shows only when top menu is active -->
        <!--        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                    <li class="">
                        <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
        <?php // echo $this->Html->image('avatars/sunny.png', array("alt" => "John Doe", "class" => "online")); ?>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                            </li>
                        </ul>
                    </li>
                </ul>-->

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span><?php echo $this->Html->link('<i class="fa fa-sign-out"></i>', array('controller' => 'AdmUsers', 'action' => 'logout'), array('escape' => false, 'title' => 'Cerrar Sesión')); ?></span>
        </div>
        <!-- end logout button -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->

    </div>
    <!-- end pulled right: nav area -->
</header>