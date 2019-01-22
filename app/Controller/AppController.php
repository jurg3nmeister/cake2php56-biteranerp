<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'DebugKit.Toolbar',
        'RequestHandler',
        'Session',
        'BittionMain',
        'BittionSecurity',
        'CurrentApp',
        'Auth' => array(
            'authenticate' => array('Form' => array('userModel' => 'AdmUser'))
            , 'loginRedirect' => array('controller' => 'adm_users', 'action' => 'home')
            , 'logoutRedirect' => array('controller' => 'adm_users', 'action' => 'login')//this is used for login and logout
            , 'loginAction' => array('controller' => 'adm_users', 'action' => 'login')
            , 'authError' => ''//it's empty because a flash auth message is used and also to avoid showing anything when trying to reach a page from login view without being logged in
            , 'authorize' => array('Controller') // Enables isAuthorized function otherwise won't work
        )
    );
    public $helpers = array(
        'Session',
        'Js',
        'SmartForm',
        'QRCode'
//        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        ,'Form' => array('className' => 'BoostCake.BoostCakeForm'),
//        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
    );

    public function isAuthorized($user) {
        return true; //everything will be authorized
//        $authorized = $this->BittionSecurity->fnAllowActionPermission($this->name, $this->action, $this->Session->read('Auth.User.AdmRole.id'));
//        if ($authorized) {
//            return true;
//        }
//        //return false // will return to the same page, not needed on this case (cause when deactive live on current page
//        $this->Session->setFlash('ACCESO DENEGADO!', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'), 'auth');
//        $this->redirect(array('controller' => 'adm_users', 'action' => 'home'));
    }

    public function beforeFilter() {
        if ($this->Session->read('Auth.User.id')) {// to avoid this message on login view when trying to reach a page without being logged in
            if ($this->name == 'AdmUsers' && $this->action == 'login' OR $this->action == 'logout') {
                $userActive = true;
            } else {
                $userActive = $this->BittionSecurity->fnCheckActiveUser($this->Session->read('Auth.User.id'));
            }
            if (!$userActive) {
                session_destroy(); //cake session destroy don't work well
                $this->Session->setFlash('Usuario desactivado!', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
                $this->redirect(array('controller' => 'adm_users', 'action' => 'login'));
            }
        }
    }

//END CLASS
}
