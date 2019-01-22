<?php

/* (c)Bittion Admin Module | Created: 31/07/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmUsersController extends AppController
{
    /*     * **************************************************VIEWS-START****************************************** */

//    Created: 31/07/2014 | Developer: reyro | Description: System Login
    public function login()
    {
//        //before everything verify if the browser is IE from windows
//        $chrome = strpos($_SERVER["HTTP_USER_AGENT"], 'Chrome') ? true : false;
////        if (eregi("MSIE", getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer", getenv("HTTP_USER_AGENT"))) {
//        if (!$chrome) {
//            $this->redirect(array('controller' => 'Pages', 'action' => 'only_chrome'));
//        }
//////////////////////////
        $this->layout = 'login';
//debug('------------------------------------------------------>'.AuthComponent::password("AdminSys001"));
        if ($this->request->is('post')) {
            if ($this->Auth->login()) { //If authentication is valid username and password
                $this->_fnFilterAuthSessionFields(); //filters Auth Session info
                $userInfo = $this->Auth->user();
                if ($userInfo['active'] == 'TRUE' && $userInfo['lc_state'] != 'LOGIC_DELETED') {
                    if ($this->_fnCreateUpdateLastLogin($userInfo['id'], $userInfo['AdmRole']['id'])) { //saves current_login and generates Menu
                        $this->Session->write('TreeMenu', $this->fnGenerateMenu($userInfo['AdmRole']['id'])); //Generates tree menu
                        $this->Session->setFlash('Usted inicio sesión como <STRONG>' . $this->Session->read('Auth.User.AdmProfile.complete_name') . '</STRONG>, con el usuario <STRONG>' . $userInfo['username'] . '</STRONG> y el rol <STRONG>' . strtolower($this->Session->read('Auth.User.AdmRole.name')) . '</STRONG>', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-info'));
                        $this->redirect($this->Auth->redirect());
                    } else {
                        session_destroy();
                        $this->Session->setFlash('Ocurrio un problema al iniciar sesión, vuelva a intentarlo', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
                    }
                } else {
                    session_destroy();
                    $this->Session->setFlash('Usuario inactivo', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
                }

            } else {
                session_destroy();
                $this->Session->setFlash('Usuario o contraseña incorrecta', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-danger'));
            }
        }
    }

//    Created: 31/07/2014 | Developer: reyro | Description: First view to show after a successful login and also the main view.
    public function home()
    {

    }

//    Created: 31/07/2014 | Developer: reyro | Decription:View logout |Observation: doesn't have .ctp file
    public function logout()
    {
        session_destroy(); // "$this->Session->destroy()" this cakephp component won't work with all servers. Therefore I'll use default php session
        $this->Session->setFlash('La sesión termino', 'alert', array('plugin' => 'BoostCake', 'class' => 'alert-info'));
        $this->redirect($this->Auth->logout());
    }


//    Created: 07/01/2015 | Developer: reyro | Description: Save (create and update) user, profile, reset password and add photo (in the future)
    public function save()
    {
        //Always empty for create
        $this->request->data['AdmProfile']['email'] = '';
        $this->request->data['AdmProfile']['sex'] = '';
        $this->request->data['AdmProfile']['di_type'] = '';

        if (isset($this->passedArgs['id'])) {
            $id = $this->passedArgs['id'];
            $this->AdmUser->id = $id;
            if (!$this->AdmUser->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $notDeleted = $this->AdmUser->find('count', array(
                'conditions' => array('AdmUser.lc_state !=' => 'LOGIC_DELETED', 'AdmUser.id' => $id),
                'recursive' => -1
            ));
            if ($notDeleted == 0) { //Or doesn't exists
                $this->redirect(array('action' => 'index'));
            }
            $tab = '';

            $this->request->data = $this->AdmUser->read(null, $id);
            $this->request->data['AdmProfile']['birthdate'] = $this->BittionMain->fnGetFormatDate($this->request->data['AdmProfile']['birthdate']);
            if (isset($this->passedArgs['tab'])) {
                $tab = $this->passedArgs['tab'];
            }

            $this->set('tab', $tab);
        }

        $this->loadModel('AdmRole');
        $roles = $this->AdmRole->find('list');
        $countries = $this->BittionMain->fnListParameters('paises', 'var_integer', 'var_string_long');
        $sex = $this->BittionMain->fnListParameters('sexo', 'var_string_short', 'var_string_long');
        $di_type = $this->BittionMain->fnListParameters('di', 'var_integer', 'var_string_long');
        $this->set(compact('roles', 'countries', 'sex', 'di_type'));

    }


//    Created: 09/08/2014 | Developer: reyro | Description: View index view, list all users | Obs: Datatable | Request: Ajax
    public function index()
    {

    }

//    Created: 09/08/2014 | Developer: reyro | Description: View for listing and restore logic deleted users | Obs: Datatable | Request: Ajax
    public function deleted_users()
    {

    }

//    Created: 01/09/2014 | Developer: reyro | Description: View for changing password on a user's account
    public function change_password()
    {

    }

//    Created: 01/09/2014 | Developer: reyro | Description: View for changing email on a user's account
    public function change_email()
    {
        $email = $this->AdmUser->AdmProfile->read(array('AdmProfile.email'), $this->Session->read('Auth.User.AdmProfile.id')); //Fields, modelId
        $this->set('email', $email['AdmProfile']['email']);
    }

    /*     * **************************************************VIEWS-END****************************************** */


    /*     * ***************************************PUBLIC FUNCTIONS-START**************************************** */

//    Created: 07/01/2015 | Developer: reyro | Description: Update AdmUser and AdmProfile with Transaction | Request: Ajax
    public function fnSave()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $unencryptedPassword = $this->_fnGeneratePassword();
            if ($this->request->data['data']['AdmUser']['id'] == '') { //create
                $this->request->data['data']['AdmUser']['username'] = strtolower($this->request->data['data']['AdmUser']['username']);
                $this->request->data['data']['AdmProfile']['email'] = strtolower($this->request->data['data']['AdmProfile']['email']);
                $this->request->data['data']['AdmUser']['password'] = AuthComponent::password($unencryptedPassword); //'12345';
                $this->request->data['data']['AdmUser']['active'] = '1';
            }
            $response = $this->AdmUser->fnSaveTransac($this->request->data['data'], $unencryptedPassword);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 09/08/2014 | Developer: reyro | Description: Update AdmProfile | Request: Ajax
    public function fnUpdateProfile()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            if ($this->request->data['data']['AdmProfile']['birthdate'] != '') {
                $this->request->data['data']['AdmProfile']['birthdate'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['AdmProfile']['birthdate']);
            }
            if ($this->request->data['data']['AdmProfile']['id'] != '') {
                try {
                    if ($this->AdmUser->AdmProfile->save($this->request->data['data'])) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
                    }
                } catch (Exception $exc) {
                    $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
                }
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/08/2014 | Developer: reyro | Description: verify unique username 
    public function fnVerifyUniqueUsername()
    {
        if ($this->RequestHandler->isAjax()) {
            $username = $this->request->data['value'];

            $response = $this->AdmUser->find('count', array(
                'conditions' => array('AdmUser.username' => $username),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/08/2014 | Developer: reyro | Description: verify unique email
    public function fnVerifyUniqueEmail()
    {
        if ($this->RequestHandler->isAjax()) {
            $email = $this->request->data['value'];
            $response = $this->AdmUser->AdmProfile->find('count', array(
                'conditions' => array('AdmProfile.email' => $email),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 08/08/2014 | Developer: reyro | Description: generate a username
    public function fnGenerateUsername()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnGenerateUserName($this->request->data['given_name'], $this->request->data['family_name']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 09/08/2014 | Developer: reyro | Description: reset a user's password with some random
    public function fnResetPassword()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            if($this->request->data['id'] == ''){
                $response = array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Primero debe guardar el usuario');
                return new CakeResponse(array('body' => json_encode($response)));
            }
            $data = array();
            $password = $this->_fnGeneratePassword();
//            $this->AdmUser->id = $this->request->data['id']; //NOT WORKING giving bugs
            $data['AdmUser']['id'] = $this->request->data['id']; //solution
            $data['AdmUser']['password'] = AuthComponent::password($password);
            if ($this->request->data['id'] != '') {
//                try {
                if ($this->AdmUser->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => $password, 'content' => 'PASSWORD_CHANGED'));
                }
//                } catch (Exception $exc) {
//                    $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
//                }
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 09/08/2014 | Developer: reyro | Description: read and list users | Obs: Datatable | Request: Ajax
    public function fnRead()
    {
        if ($this->RequestHandler->isAjax()) {
            $model = 'AdmUser';  //the key
            $fields = array($model . '.username', $model . '.active', 'AdmProfile.given_name', 'AdmProfile.family_name', 'AdmRole.name', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.username) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmProfile.given_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmProfile.family_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmRole.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            );
//            $conditions = array('OR' => $conditionsOR, 'AdmUser.id !=' => 1, 'AdmUser.lc_state !=' => 'LOGIC_DELETED');//if there is undeletable user #SUPERUSER
            $conditions = array('OR' => $conditionsOR, 'AdmUser.lc_state !=' => 'LOGIC_DELETED');
            /////////////////////////////////////
            $this->$model->recursive = 0;
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
            $total = $this->$model->find("count", array(
                'conditions' => $conditions
            ));

            $array = array("sEcho" => $this->request->data['sEcho']);
            $array["aaData"] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                $array["aaData"][$key][0] = $value[$model]["username"];
                $array["aaData"][$key][1] = array('id' => $value[$model]["id"], 'active' => $value[$model]["active"]); //$this->_fnGenerateControlSwitch($value[$model]["active"], $value[$model]["id"]); //$value[$model]["active"];
                $array["aaData"][$key][2] = $value['AdmProfile']['given_name'];
                $array["aaData"][$key][3] = $value['AdmProfile']['family_name'];
                $array["aaData"][$key][4] = $value['AdmRole']['name'];
                $array["aaData"][$key][5] = ''; //empty for edit & delete buttons
//                $counter++;
            }
            $array["iTotalRecords"] = $total;
            $array["iTotalDisplayRecords"] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 12/08/2014 | Developer: reyro | Description: read and list Logic Deleted Users | Obs: Datatable | Request: Ajax
    public function fnReadDeletedUsers()
    {
        if ($this->RequestHandler->isAjax()) {
            $model = 'AdmUser';  //the key
            $fields = array($model . '.username', 'AdmProfile.given_name', 'AdmProfile.family_name', 'AdmRole.name', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.username) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmProfile.given_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmProfile.family_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            , 'lower(AdmRole.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            );
            $conditions = array('OR' => $conditionsOR, 'AdmUser.id !=' => 1, 'AdmUser.lc_state' => 'LOGIC_DELETED');
            /////////////////////////////////////
            $this->$model->recursive = 0;
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
            $total = $this->$model->find("count", array(
                'conditions' => $conditions
            ));

            $array = array("sEcho" => $this->request->data['sEcho']);
            $array["aaData"] = array();
            foreach ($arrayPaginate as $key => $value) {
                $array["aaData"][$key][0] = $value[$model]["username"];
                $array["aaData"][$key][1] = $value['AdmProfile']['given_name'];
                $array["aaData"][$key][2] = $value['AdmProfile']['family_name'];
                $array["aaData"][$key][3] = $value['AdmRole']['name'];
                $array["aaData"][$key][4] = $value[$model]["id"];
            }
            $array["iTotalRecords"] = $total;
            $array["iTotalDisplayRecords"] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/08/2014 | Developer: reyro | Description: put lc_state LOGIC_DELETED to a user | Request:Ajax 
    public function fnLogicDelete()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            if ($this->Session->read('Auth.User.id') == $this->request->data['id']) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada!', 'content' => 'No puede eliminar su propia cuenta'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
            $data = array();
//            $this->AdmUser->id = $this->request->data['id']; //doesn't work all the time
            $data['AdmUser']['id'] = $this->request->data['id'];
            $data['AdmUser']['lc_state'] = 'LOGIC_DELETED';
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmUser->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'DELETE'));
                    }
                } catch (Exception $exc) {
                    $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
                }
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/08/2014 | Developer: reyro | Description: put lc_state ELABORATED to restore a user | Request:Ajax 
    public function fnRestore()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
//            $this->AdmUser->id = $this->request->data['id'];
            $data['AdmUser']['id'] = $this->request->data['id'];
            $data['AdmUser']['lc_state'] = 'ELABORATED';
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmUser->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'RESTORE'));
                    }
                } catch (Exception $exc) {
                    $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
                }
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/08/2014 | Developer: reyro | Description: change state active true or false on a user
    public function fnActivate()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            //Avoid deactivating oneself
            if ($this->Session->read('Auth.User.id') == $this->request->data['id'] && $this->request->data['active'] == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada!', 'content' => 'No puede desactivar su propia cuenta'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
            $data = array();
//            $this->AdmUser->id = $this->request->data['id'];
            $data['AdmUser']['id'] = $this->request->data['id'];
            $data['AdmUser']['active'] = $this->request->data['active'];
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmUser->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'ACTIVE'));
                    }
                } catch (Exception $exc) {
                    $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
                }
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 30/08/2014 | Developer: reyro | Description: generate array multilevel for create menu
    public function fnGenerateMenu($roleId)
    {
        $level1 = $this->_fnGetMenus(null, $roleId);
        $level2 = $this->_fnGetMenus($level1['menusIds'], $roleId);
        $level3 = $this->_fnGetMenus($level2['menusIds'], $roleId);
        return $multiLevelMenu = $this->_fnGenerateMultiLevelMenu($level1['menus'], $level2['menus'], $level3['menus']);
    }

//    Created: 01/09/2014 | Developer: reyro | Description: validates if current password is correct
    public function fnValidateCurrentPassword()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnValidateCurrentPassword($this->Session->read('Auth.User.id'), $this->request->data['currentPassword']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 01/09/2014 | Developer: reyro | Description: updates current password
    public function fnUpdatePassword()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $userId = $this->Session->read('Auth.User.id');
            $userPassword = AuthComponent::password($this->request->data['password']);
            $data = array();
            //Second validation in case view js is deactivated somehow
            $existsPassword = $this->_fnValidateCurrentPassword($userId, $this->request->data['currentPassword']);
            if ($existsPassword == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción Denegada!', 'content' => 'La contraseña actual es incorrecta'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
            //Update
//            $this->AdmUser->id = $userId; //it doesn't always work. DON'T DO IT.
            $data['AdmUser']['id'] = $userId; //it always works
            $data['AdmUser']['password'] = $userPassword;
            if ($this->AdmUser->save($data)) {
                $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'PASSWORD_CHANGED'));
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 01/09/2014 | Developer: reyro | Description: updates current password
    public function fnUpdateEmail()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $userId = $this->Session->read('Auth.User.id');
            $profileId = $this->Session->read('Auth.User.AdmProfile.id');
            $profileEmail = $this->request->data['email'];
            $data = array();
            //Second validation in case view js is deactivated somehow
            $existsPassword = $this->_fnValidateCurrentPassword($userId, $this->request->data['password']);
            if ($existsPassword == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción Denegada!', 'content' => 'La contraseña es incorrecta'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
            //Update
            $this->AdmUser->AdmProfile->id = $profileId;
            $data['AdmProfile']['email'] = $profileEmail;
            if ($this->AdmUser->AdmProfile->save($data)) {
                $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'EMAIL_CHANGED', 'data' => $profileEmail));
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

    /*     * ***************************************PUBLIC FUNCTIONS-END**************************************** */


    /*     * ***************************************PRIVATE FUNCTIONS-START**************************************** */

//    Created: 01/09/2014 | Developer: reyro | Description: validates current password
    private function _fnValidateCurrentPassword($userId, $userPassword)
    {
        $response = $this->AdmUser->find('count', array(
            'conditions' => array('AdmUser.id' => $userId, 'AdmUser.password' => AuthComponent::password($userPassword)),
            'recursive' => -1
        ));
        return $response;
    }

//    Created: 10/08/2014 | Developer: reyro | Description: On login update field last_login | Obs: must need special exception on trigger adm_users
    private function _fnCreateUpdateLastLogin($userId, $roleId)
    {
        $login = $this->AdmUser->AdmLogin->find('first', array('fields' => array('AdmLogin.id', 'AdmLogin.date'), 'conditions' => array('AdmLogin.adm_user_id' => $userId)));
        $lastLogin = '';
        if (isset($login['AdmLogin']['id'])) {
            $data['AdmLogin']['id'] = $login['AdmLogin']['id'];
//            $lastLogin = $this->BittionMain->fnSetFormatDateTime(date("d/m/Y/H:i:s", strtotime($login['AdmLogin']['date'])));
            $lastLogin = $this->BittionMain->fnSetFormatDateTimeLastLogin($login['AdmLogin']['date']);
        }
        $date = new DateTime();
        $data['AdmLogin']['adm_user_id'] = $userId;
//        $dateTime = $this->BittionMain->fnSetFormatDateTime($date->format('d/m/Y/H:i:s'));
        $dateTime = $this->BittionMain->fnSetFormatDateTime($date->format('d/m/Y H:i:s'));
        $data['AdmLogin']['date'] = $dateTime;
        try {
            if (!$this->AdmUser->AdmLogin->save($data)) {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }
        $this->Session->write('Auth.User.AdmLogin', $lastLogin);
        return true;
    }

//    Created: 30/08/2014 | Developer: reyro | Description: get menus arrays for every level according to parents   
    private function _fnGetMenus($parents, $roleId)
    {
        $this->loadModel('AdmMenu');
        $this->AdmMenu->unbindModel(array('hasMany' => array('AdmRolesMenu')));
        $this->AdmMenu->bindModel(array(
                'hasOne' => array(
                    'AdmRolesMenu' => array('foreignKey' => false, 'conditions' => array('AdmRolesMenu.adm_menu_id = AdmMenu.id')),
                    'AdmController' => array('foreignKey' => false, 'conditions' => array('AdmController.id = AdmAction.adm_controller_id')),
                )
            )
        );
        $menus = $this->AdmMenu->find('all', array(
            'fields' => array('AdmController.name', 'AdmAction.name', 'AdmMenu.id', 'AdmMenu.name', 'AdmMenu.order_menu', 'AdmMenu.parent', 'AdmMenu.icon'
            , '(SELECT COUNT(id) FROM adm_menus WHERE parent = "AdmMenu"."id") AS children'),
            'conditions' => array('AdmMenu.parent' => $parents, 'AdmRolesMenu.adm_role_id' => $roleId),
            "order" => array("AdmMenu.order_menu", "AdmMenu.name"),
        ));
        $menusIds = array();
        foreach ($menus as $key => $value) {
            $menusIds[$key] = $value['AdmMenu']['id'];
        }
        return array('menus' => $menus, 'menusIds' => $menusIds);
    }

//    Created: 30/08/2014 | Developer: reyro | Description: order 3 levels arrays into 1 multi level array | Obs: create html tags in the controller to avoid using loops everytime a page loads (for perfomance)
    private function _fnGenerateMultiLevelMenu($menus1, $menus2, $menus3)
    {
        //3 levels
        $html = '<ul>';
        foreach ($menus1 as $menu1) { //LEVEL 1
            $menuId = $this->_fnGenerateMenuId($menu1['AdmController']['name'], $menu1['AdmAction']['name']);
            $html .= '<li ' . $menuId . '>';
            $url = $this->_fnMenuCheckHref($menu1['AdmController']['name'], $menu1['AdmAction']['name']);
            $icon = $this->_fnMenuCheckIcon($menu1['AdmMenu']['icon']);
            $html .= '<a href="' . $url . '">' . $icon . ' <span class="menu-item-parent">' . $menu1['AdmMenu']['name'] . '</span></a>';
            if ($menu1[0]['children'] > 0) {
                $html .= '<ul>';
            }
            foreach ($menus2 as $menu2) { //LEVEL 2
                if ($menu1['AdmMenu']['id'] == $menu2['AdmMenu']['parent']) {
                    $menuId = $this->_fnGenerateMenuId($menu2['AdmController']['name'], $menu2['AdmAction']['name']);
                    $html .= '<li ' . $menuId . '>';
                    $url = $this->_fnMenuCheckHref($menu2['AdmController']['name'], $menu2['AdmAction']['name']);
                    $icon = $this->_fnMenuCheckIcon($menu2['AdmMenu']['icon']);
                    $html .= '<a href="' . $url . '" >' . $icon . ' ' . $menu2['AdmMenu']['name'] . '</a>';
                    if ($menu2[0]['children'] > 0) {
                        $html .= '<ul>';
                    }
                    foreach ($menus3 as $menu3) { //LEVEL 3
                        if ($menu2['AdmMenu']['id'] == $menu3['AdmMenu']['parent']) {
                            $menuId = $this->_fnGenerateMenuId($menu3['AdmController']['name'], $menu3['AdmAction']['name']);
                            $html .= '<li ' . $menuId . '>';
                            $url = $this->_fnMenuCheckHref($menu3['AdmController']['name'], $menu3['AdmAction']['name']);
                            $icon = $this->_fnMenuCheckIcon($menu3['AdmMenu']['icon']);
                            $html .= '<a href="' . $url . '">' . $icon . ' ' . $menu3['AdmMenu']['name'] . '</a>';
                            $html .= '</li>';
                        }//if level 3
                    }//LEVEL 3
                    if ($menu2[0]['children'] > 0) {
                        $html .= '</ul>';
                    }
                    $html .= '</li>';
                }//if level2
            }//LEVEL 2
            if ($menu1[0]['children'] > 0) {
                $html .= '</ul>';
            }
            $html .= '</li>';
        }//LEVEL 1
        $html .= '</ul>';
        return $html;
    }

//    Created: 01/09/2014 | Developer: reyro | Description: adds and id to an <li> if it has an action
    private function _fnGenerateMenuId($controller, $action)
    {
        $menuId = '';
        if ($controller != '') {
            $menuId = 'id="menu-' . $controller . '-' . $action . '"';
        }
        return $menuId;
    }

//    Created: 30/08/2014 | Developer: reyro | Description: checks if href must hava an url
    private function _fnMenuCheckHref($controller, $action)
    {
        $url = '#';
        if ($controller != '') {
            if ($action == 'index') {
                $action = '';
            } else {
                $action = '/' . $action;
            }
            $url = $this->webroot . $controller . $action;
        }
        return $url;
    }

//    Created: 30/08/2014 | Developer: reyro | Description: checks if a menu must have an icon
    private function _fnMenuCheckIcon($icon)
    {
        if ($icon != '') {
            $icon = '<i class="fa fa-lg fa-fw ' . $icon . '"></i>';
        }
        return $icon;
    }

//    Created: 30/08/2014 | Developer: reyro | Description: filter unncessary fields
    private function _fnFilterAuthSessionFields()
    {
        $fields = array('User.id' => '', 'User.username' => '', 'User.active' => '', 'User.lc_state' => '', 'User.AdmRole.name' => '', 'User.AdmRole.id' => '', 'User.AdmProfile.id' => ''
        , 'User.AdmProfile.given_name' => '', 'User.AdmProfile.family_name' => '', 'User.AdmProfile.photo' => '', 'User.AdmProfile.sex' => '');
        foreach ($fields as $key => $value) {
            $fields[$key] = $this->Session->read('Auth.' . $key);
        }
        $completeName = $fields['User.AdmProfile.given_name'] . ' ' . $fields['User.AdmProfile.family_name'];
        $firstName = explode(' ', $fields['User.AdmProfile.given_name']);
        $fields['User.AdmProfile.given_name'] = $firstName[0];
        $firstName = explode(' ', $fields['User.AdmProfile.family_name']);
        $fields['User.AdmProfile.family_name'] = $firstName[0];
        $this->Session->delete('Auth');
        foreach ($fields as $key => $value) {
            $this->Session->write('Auth.' . $key, $value);
        }
        $this->Session->write('Auth.User.AdmProfile.complete_name', $completeName);
    }

//    Created: 07/08/2014 | Developer: reyro | Description: generate a random password
    private function _fnGeneratePassword($length = 8)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

//  Created: 08/08/2014 | Developer: reyro | Description: generates a username    
    private function _fnGenerateUserName($givenName, $familyName)
    {

        $givenName = explode(' ', strtolower($givenName));
        $familyName = explode(' ', strtolower($familyName));
        $userNameSimple = substr(trim($givenName[0]), 0, 1) . trim($familyName[0]);
        $userNameFull = '';

        $countFamilyName = count($familyName);
        if ($countFamilyName > 1) {
            $lastFamilyName = $countFamilyName - 1;
            $userNameFull = $userNameSimple . substr(trim($familyName[$lastFamilyName]), 0, 1);
        }

        if ($userNameFull == '') {
            $userNameAux = $userNameSimple;
        } else {
            $userNameAux = $userNameFull;
        }

        $userName = $userNameAux;
        $founded = $this->AdmUser->find('count', array('conditions' => array('AdmUser.username LIKE' => '%' . $userNameAux . '%')));

        if ($founded > 0) {
            $userName = $userNameAux . '_' . ($founded + 1);
        }
        return $userName;
    }

    /*     * ***************************************PRIVATE FUNCTIONS-END**************************************** */
}
