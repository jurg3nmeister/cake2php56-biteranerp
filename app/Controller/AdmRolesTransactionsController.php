<?php

/* (c)Bittion Admin Module | Created: 21/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmRolesTransactionsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 21/08/2014 | Developer: reyro | Description: View index, list and save roles actions | Request:Ajax    
    public function index() {
        $this->loadModel('AdmModule');
        $modules = $this->AdmModule->find('list', array('order' => array('AdmModule.name' => 'ASC')));
        $roles = $this->AdmRolesTransaction->AdmRole->find('list');
        $this->set(compact('modules', 'roles'));
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 21/08/2014 | Developer: reyro | Description: function list all action roles | Request:Ajax    
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnCheckControllerActions($this->request->data['moduleId'], $this->request->data['roleId']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 21/08/2014 | Developer: reyro | Description: insert and delete roles actions | Request:Ajax | Obs: Model Transaction    
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            $oldValues = $this->_fnFindOldValues($this->request->data['AdmRolesTransaction']['adm_role_id'], $this->request->data['AdmModule']['id']);
            $newValues = array();
            if (isset($this->request->data['transactionsIds'])) {
                $newValues = $this->request->data['transactionsIds'];
            }
            $insert = array_diff($newValues, $oldValues);
            $delete = array_diff($oldValues, $newValues);
            $response = $this->AdmRolesTransaction->fnSaveAndDelete($this->request->data['AdmRolesTransaction']['adm_role_id'], $insert, $delete);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

////////////////////////PRIVATE FUNCTIONS 
//    Created: 21/08/2014 | Developer: reyro | Description: check if exists a role action 
    private function _fnCheckControllerActions($module, $role) {
        $transactions = $this->_fnGetControllersTransactions($module);
        $rolesTransactions = $this->AdmRolesTransaction->find("list", array(
            "fields" => array('AdmRolesTransaction.id', "AdmRolesTransaction.adm_transaction_id"),
            "conditions" => array("adm_transaction_id" => $transactions['transactionsIds'], "adm_role_id" => $role)
        ));

        foreach ($transactions['transactionsControllers'] as $controllerName => $controllerObject) {
            foreach ($controllerObject as $transactionName => $actionObject) {
                if (in_array($actionObject['transactionId'], $rolesTransactions)) { //item_find, array
                    $transactions['transactionsControllers'][$controllerName][$transactionName]['checked'] = true; //set value true
                }
            }
        }
        return $transactions['transactionsControllers'];
    }

//    Created: 21/08/2014 | Developer: reyro | Description: creates array controllers actions
    private function _fnGetControllersTransactions($module) {
        $this->AdmRolesTransaction->AdmTransaction->unbindModel(array('hasMany' => array('AdmMenu')));
        $transactionsControllersCakeFormat = $this->AdmRolesTransaction->AdmTransaction->find('all', array(
            'fields' => array('AdmTransaction.id', 'AdmTransaction.name', 'AdmController.id', 'AdmController.name'),
            'order' => array('AdmController.name', 'AdmTransaction.name')
            , 'conditions' => array('AdmController.adm_module_id' => $module)
        ));

        $transactionsControllers = array();
        $transactionsIds = array();
        foreach ($transactionsControllersCakeFormat as $value) {
            $controllerName = Inflector::camelize($value['AdmController']['name']);
            $transactionName = $value['AdmTransaction']['name'];
            $transactionId = $value['AdmTransaction']['id'];
            $transactionsControllers[$controllerName][$transactionName] = array('transactionId' => $transactionId, 'transactionName' => $transactionName, 'checked' => false);
            $transactionsIds[$transactionId] = $transactionId;
        }
        return array('transactionsControllers' => $transactionsControllers, 'transactionsIds' => $transactionsIds);
    }

//    Created: 02/10/2014 | Developer: reyro | Description: find old values
    private function _fnFindOldValues($roleId, $moduleId) {
        $this->AdmRolesTransaction->unbindModel(array('belongsTo' => array('AdmRole', 'AdmTransaction')));
        $this->AdmRolesTransaction->bindModel(array('hasOne' => array(
                'AdmTransaction' => array('foreignKey' => false, 'conditions' => array('AdmTransaction.id = AdmRolesTransaction.adm_transaction_id')),
                'AdmController' => array('foreignKey' => false, 'conditions' => array('AdmController.id = AdmTransaction.adm_controller_id')),
        )));
        $oldValues = $this->AdmRolesTransaction->find('all', array(
            'fields' => array('AdmRolesTransaction.adm_transaction_id', 'AdmRolesTransaction.adm_transaction_id'),
            'conditions' => array(
                'AdmRolesTransaction.adm_role_id' => $roleId,
                'AdmController.adm_module_id' => $moduleId
            )
        ));
        $formatedOldValues = array();
        foreach ($oldValues as $value) {
            $formatedOldValues[$value['AdmRolesTransaction']['adm_transaction_id']] = $value['AdmRolesTransaction']['adm_transaction_id'];
        }
        return $formatedOldValues;
    }

////// End Controller	
}
