<?php

/* (c)Bittion Admin Module | Created: 21/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmTransitionsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 21/08/2014 | Developer: reyro | Description: View index, CRUD for states, transactions and transitions | Obs: All in one
    public function index() {
        $this->loadModel('AdmController');
        $controllers = $this->AdmController->find('list');
        $counter = 0;
        $controllerId = 0;
        foreach ($controllers as $key => $value) {
            $controllers[$key] = Inflector::camelize($value);
            if ($counter == 0) {
                $controllerId = $key;
            }
            $counter++;
        }
//        $controllerId = key($controllers); // BUG not working, no idea getting second key element not first :/, thats why use counter++
        
        $statesTransactions = $this->_fnGeStatesTransactions($controllerId);
        $this->set('states', $statesTransactions['states']);
        $this->set('transactions', $statesTransactions['transactions']);
        $this->set('controllers', $controllers);
    }

////////////////////////PUBLIC FUNCTIONS    
//    Created: 21/08/2014 | Developer: reyro | Description: data for filling 3 tables (transitions, states, transactions) | Request: Ajax
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            $controllerId = $this->request->data['controllerId'];
            $json = array();
            $json['Transitions'] = $this->_fnGetTransitions($controllerId);
            $json['States'] = $this->_fnGetStates($controllerId);
            $json['Transactions'] = $this->_fnGetTransactions($controllerId);
            return new CakeResponse(array('body' => json_encode($json)));  //convert to json format and send
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function create transition | Request: Ajax
    public function fnCreateTransition() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmTransition->create();
            try {
                if ($this->AdmTransition->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function create state | Request: Ajax
    public function fnCreateState() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->request->data['data']['AdmState']['adm_controller_id'] = $this->request->data['controllerId'];
            $this->request->data['data']['AdmState']['name'] = strtoupper($this->request->data['data']['AdmState']['name']);
            $this->AdmTransition->AdmState->create();
            try {
                if ($this->AdmTransition->AdmState->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function create transition | Request: Ajax
    public function fnCreateTransaction() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->request->data['data']['AdmTransaction']['adm_controller_id'] = $this->request->data['controllerId'];
            $this->request->data['data']['AdmTransaction']['name'] = strtoupper($this->request->data['data']['AdmTransaction']['name']);
            $this->AdmTransition->AdmTransaction->create();
            try {
                if ($this->AdmTransition->AdmTransaction->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Delete transition| Request: Ajax 	
    public function fnDeleteTransition() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmTransition->id = $this->request->data['id'];
            try {
                if ($this->AdmTransition->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Delete state| Request: Ajax 	
    public function fnDeleteState() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmTransition->AdmState->id = $this->request->data['id'];
            try {
                if ($this->AdmTransition->AdmState->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Delete transaction| Request: Ajax 	
    public function fnDeleteTransaction() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmTransition->AdmTransaction->id = $this->request->data['id'];
            try {
                if ($this->AdmTransition->AdmTransaction->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function udpating one field from State with x-editable jquery plugin | Request: Ajax 	 	
    public function fnUpdateXEditableState() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            if ($this->request->data['pk'] != '' && $this->request->data['name'] != '') { //Always send id inside $this->request->data
                $this->AdmTransition->AdmState->id = $this->request->data['pk'];
                $field = explode('-', $this->request->data['name']); //name is the id of <a><a/>, I'll use it to make this dinamycally with any other field not only descpription in this case
                $value = $this->request->data['value'];
                if($field[0] == 'name'){
                    $value = strtoupper($value);
                }
                $data['AdmState'][$field[0]] = $value;
                try {
                    if ($this->AdmTransition->AdmState->save($data)) {
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
    
//    Created: 22/08/2014 | Developer: reyro | Description: Function udpating one field from Transaction with x-editable jquery plugin | Request: Ajax 	 	
    public function fnUpdateXEditableTransaction() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            if ($this->request->data['pk'] != '' && $this->request->data['name'] != '') { //Always send id inside $this->request->data
                $this->AdmTransition->AdmTransaction->id = $this->request->data['pk'];
                $field = explode('-', $this->request->data['name']); //name is the id of <a><a/>, I'll use it to make this dinamycally with any other field not only descpription in this case
                $value = $this->request->data['value'];
                if($field[0] == 'name'){
                    $value = strtoupper($value);
                }
                $data['AdmTransaction'][$field[0]] = $value;
                try {
                    if ($this->AdmTransition->AdmTransaction->save($data)) {
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

////////////////////////PRIVATE FUNCTIONS  
//    Created: 22/08/2014 | Developer: reyro | Description: Get states and transactions by controller
    private function _fnGeStatesTransactions($controllerId) {
        $this->loadModel('AdmState');
        $states = $this->AdmState->find('list', array('conditions' => array('AdmState.adm_controller_id' => $controllerId)));
        $this->loadModel('AdmTransaction');
        $transactions = $this->AdmTransaction->find('list', array('conditions' => array('AdmTransaction.adm_controller_id' => $controllerId)));
        return array('states' => $states, 'transactions' => $transactions);
    }

//    Created: 21/08/2014 | Developer: reyro | Description: Get transitions by Controller
    private function _fnGetTransitions($controllerId) {
        $controller = 'AdmTransition'; //only replace this variable will help a lot of work for main controller
        //Query/Search
        $searchConditions = array(
            'AdmTransaction.adm_controller_id' => $controllerId
        );
        $this->paginate = array(
            'order' => array($controller . '.adm_transaction_id' => 'asc', 'AdmFinalState.name' => 'ASC'),
            'limit' => 50,
            'fields' => array(
                'AdmTransition.id'
                , 'AdmState.name'
                , 'AdmTransaction.name'
                , 'AdmFinalState.name'
            ),
            'conditions' => $searchConditions
        );
        $data = $this->paginate($controller);

        //Data Json Formating
        $json = array();
        foreach ($data as $key => $value) {
            $json[$key]['id'] = $value['AdmTransition']['id'];
            $json[$key]['initialState'] = $value['AdmState']['name'];
            $json[$key]['transaction'] = $value['AdmTransaction']['name'];
            $json[$key]['finalState'] = $value['AdmFinalState']['name'];
        }

        return $json;
    }

//    Created: 21/08/2014 | Developer: reyro | Description: Get states by Controller
    private function _fnGetStates($controllerId) {
        $controller = 'AdmState'; //only replace this variable will help a lot of work for main controller
        //Query/Search
        $searchConditions = array(
            'AdmState.adm_controller_id' => $controllerId
        );
        $this->paginate = array(
            'recursive' => -1,
//				'order' => array($controller . '.name' => 'ASC'), //it's not sorting by name ??
            'limit' => 50,
            'fields' => array(
                $controller . '.id'
                , $controller . '.name'
                , $controller . '.description'
            ),
            'conditions' => $searchConditions
        );
        $data = $this->paginate($controller);

        //Data Json Formating
        $json = array();
        foreach ($data as $key => $value) {
            $json[$key]['id'] = $value[$controller]['id'];
            $json[$key]['name'] = $value[$controller]['name'];
            $json[$key]['description'] = $value[$controller]['description'];
        }

        return $json;
    }

//    Created: 21/08/2014 | Developer: reyro | Description: Get transactions by Controller    
    private function _fnGetTransactions($controllerId) {
        $controller = 'AdmTransaction'; //only replace this variable will help a lot of work for main controller
        //Query/Search
        $searchConditions = array(
            'AdmTransaction.adm_controller_id' => $controllerId
        );
        $this->paginate = array(
//				'order' => array($controller . '.name' => 'asc'),
            'limit' => 50,
            'fields' => array(
                $controller . '.id'
                , $controller . '.name'
                , $controller . '.description'
            ),
            'conditions' => $searchConditions
        );
        $data = $this->paginate($controller);

        //Data Json Formating
        $json = array();
        foreach ($data as $key => $value) {
            $json[$key]['id'] = $value[$controller]['id'];
            $json[$key]['name'] = $value[$controller]['name'];
            $json[$key]['description'] = $value[$controller]['description'];
        }
        return $json;
    }

//***************************** END CONTROLLERS ********************************    
}
