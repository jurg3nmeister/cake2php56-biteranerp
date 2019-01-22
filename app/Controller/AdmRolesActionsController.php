<?php

/* (c)Bittion Admin Module | Created: 20/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmRolesActionsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 20/08/2014 | Developer: reyro | Description: View index, list and save roles actions | Request:Ajax 
    public function index() {
        $this->loadModel('AdmModule');
        $modules = $this->AdmModule->find('list', array('order' => array('AdmModule.name' => 'ASC')));
        $roles = $this->AdmRolesAction->AdmRole->find('list');
        $this->set(compact('modules', 'roles'));
        
        
//        $this->AdmRolesAction->unbindModel(array('belongsTo' => array('AdmRole', 'AdmAction')));
//        $this->AdmRolesAction->bindModel(array('hasOne' => array(
//                'AdmAction' => array('foreignKey' => false, 'conditions' => array('AdmAction.id = AdmRolesAction.adm_action_id')),
//                'AdmController' => array('foreignKey' => false, 'conditions' => array('AdmController.id = AdmAction.adm_controller_id')),
//            )));
//        
//        $oldValues = $this->AdmRolesAction->find('all', array(
//            'fields' => array('AdmRolesAction.adm_action_id', 'AdmRolesAction.adm_action_id'),
////            'recursive' => 2,
//            'conditions' => array(
//                'AdmRolesAction.adm_role_id' => 1,
//                'AdmController.adm_module_id' => 1
//            )
//        ));
//        $oldValues = $this->AdmRolesAction->find('all');
//        debug($oldValues);
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 20/08/2014 | Developer: reyro | Description: function list all action roles | Request:Ajax
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnCheckControllerActions($this->request->data['moduleId'], $this->request->data['roleId']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 20/08/2014 | Developer: reyro | Description: insert and delete roles actions | Request:Ajax | Obs: Model Transaction
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            $oldValues = $this->_fnFindOldValues($this->request->data['AdmRolesAction']['adm_role_id'], $this->request->data['AdmModule']['id']);
            $newValues = array();
            if (isset($this->request->data['actionsIds'])) {
                $newValues = $this->request->data['actionsIds'];
            }
            $insert = array_diff($newValues, $oldValues);
            $delete = array_diff($oldValues, $newValues);
            $response = $this->AdmRolesAction->fnSaveAndDelete($this->request->data['AdmRolesAction']['adm_role_id'], $insert, $delete);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

////////////////////////PRIVATE FUNCTIONS 
//    Created: 20/08/2014 | Developer: reyro | Description: check if exists a role action 
    private function _fnCheckControllerActions($module, $role) {
        $actions = $this->_fnGetControllersActions($module);
        $rolesActions = $this->AdmRolesAction->find("list", array(
            "fields" => array('AdmRolesAction.id', "adm_action_id"),
            "conditions" => array("adm_action_id" => $actions['actionsIds'], "adm_role_id" => $role)
        ));

        foreach ($actions['actionsControllers'] as $controllerName => $controllerObject) {
            foreach ($controllerObject as $actionName => $actionObject) {
                if (in_array($actionObject['actionId'], $rolesActions)) { //item_find, array
                    $actions['actionsControllers'][$controllerName][$actionName]['checked'] = true; //set value true
                }
            }
        }
        return $actions['actionsControllers'];
    }

//    Created: 20/08/2014 | Developer: reyro | Description: creates array controllers actions
    private function _fnGetControllersActions($module) {
        $this->AdmRolesAction->AdmAction->unbindModel(array('hasMany' => array('AdmMenu')));
        $actionsControllersCakeFormat = $this->AdmRolesAction->AdmAction->find('all', array(
            'fields' => array('AdmAction.id', 'AdmAction.name', 'AdmController.id', 'AdmController.name'),
            'order' => array('AdmController.name', 'AdmAction.name')
            , 'conditions' => array('AdmController.adm_module_id' => $module)
        ));

        $actionsControllers = array();
        $actionsIds = array();
        foreach ($actionsControllersCakeFormat as $value) {
            $controllerName = Inflector::camelize($value['AdmController']['name']);
            $actionName = $value['AdmAction']['name'];
            $actionId = $value['AdmAction']['id'];
            $actionsControllers[$controllerName][$actionName] = array('actionId' => $actionId, 'actionName' => $actionName, 'checked' => false);
            $actionsIds[$actionId] = $actionId;
        }
        return array('actionsControllers' => $actionsControllers, 'actionsIds' => $actionsIds);
    }

//    Created: 02/10/2014 | Developer: reyro | Description: find old values
    private function _fnFindOldValues($roleId, $moduleId) {
        $this->AdmRolesAction->unbindModel(array('belongsTo' => array('AdmRole', 'AdmAction')));
        $this->AdmRolesAction->bindModel(array('hasOne' => array(
                'AdmAction' => array('foreignKey' => false, 'conditions' => array('AdmAction.id = AdmRolesAction.adm_action_id')),
                'AdmController' => array('foreignKey' => false, 'conditions' => array('AdmController.id = AdmAction.adm_controller_id')),
            )));
        $oldValues = $this->AdmRolesAction->find('all', array(
            'fields' => array('AdmRolesAction.adm_action_id', 'AdmRolesAction.adm_action_id'),
            'conditions' => array(
                'AdmRolesAction.adm_role_id' => $roleId,
                'AdmController.adm_module_id' => $moduleId
            )
        ));
        $formatedOldValues = array();
        foreach($oldValues as $value){
            $formatedOldValues[$value['AdmRolesAction']['adm_action_id']] = $value['AdmRolesAction']['adm_action_id'];
        }
        return $formatedOldValues;
    }

////// End Controller	
}
