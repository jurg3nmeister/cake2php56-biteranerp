<?php

/* (c)Bittion Admin Module | Created: 18/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmActionsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 18/08/2014 | Developer: reyro | Description: View Create 
    public function create() {
        $modules = $this->AdmAction->AdmController->AdmModule->find('list', array('recursive' => -1, 'order' => array('AdmModule.id' => 'ASC')));
        $this->set(compact('modules'));
    }

//    Created: 18/08/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 18/08/2014 | Developer: reyro | Description: Function Create | Request: Ajax
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
//            $this->request->data['data']['AdmAction']['initials'] = strtolower($this->request->data['data']['AdmAction']['initials']);
            unset($this->request->data['data']['AdmModule']['id']);
            $this->AdmAction->create();
            try {
                if ($this->AdmAction->save($this->request->data['data'])) {
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

    //    Created: 18/08/2014 | Developer: reyro | Description: Function udpating one field by time with x-editable jquery plugin | Request: Ajax 	 	
    public function fnUpdateXEditable() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            if ($this->request->data['pk'] != '' && $this->request->data['name'] != '') { //Always send id inside $this->request->data
//                $this->AdmAction->id = $this->request->data['pk'];
                $data['AdmAction']['id'] = $this->request->data['pk'];
                $field = explode('-', $this->request->data['name']); //name is the id of <a><a/>, I'll use it to make this dinamycally with any other field not only descpription in this case
                $data['AdmAction'][$field[0]] = $this->request->data['value'];
                try {
                    if ($this->AdmAction->save($data)) {
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

//    Created: 18/08/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'AdmAction';
            $fields = array('AdmController.name', $model . '.name', $model . '.description', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.description) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'replace(AdmController.name, \'_\', \'\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR /* ,conditions AND */);
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
            $total = $this->$model->find('count', array(
                'conditions' => $conditions
            ));
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = Inflector::camelize($value['AdmController']['name']);
                $array['aaData'][$key][1] = $value[$model]['name'];
                $array['aaData'][$key][2] = $value[$model]['description'];
                $array['aaData'][$key][3] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 18/08/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmAction->id = $this->request->data['id'];
            try {
                if ($this->AdmAction->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 18/08/2014 | Developer: reyro | Description: get controllers for select| Request: Ajax 	
    public function fnReadControllers() {
        if ($this->RequestHandler->isAjax()) {
            $controllers = $this->AdmAction->AdmController->find('list', array('conditions' => array('AdmController.adm_module_id' => $this->request->data['moduleId'])));

            $response = array();
            foreach ($controllers as $controlKey => $controlValue) {
                $response[$controlKey] = Inflector::camelize($controlValue);
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 18/08/2014 | Developer: reyro | Description: get actions name available for select| Request: Ajax 	
    public function fnReadActionsAvailable() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnGetActionsAvailable($this->request->data['controllerId'], $this->request->data['controllerName']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

////////////////////////PRIVATE FUNCTIONS    
//    Created: 18/08/2014 | Developer: reyro | Description: get actions available for select| Request: Ajax
    private function _fnGetActionsAvailable($controllerId, $controllerName) {
        App::import('Controller', $controllerName);
        //Inflector:camelize is needed to filter some values like isAuthorized
        $parentClassMethods = get_class_methods(get_parent_class(Inflector::camelize($controllerName) . 'Controller'));
        $subClassMethods = get_class_methods(Inflector::camelize($controllerName) . 'Controller');
//		debug($subClassMethods);
//		debug($parentClassMethods);
        $classMethods = array();
        if ($subClassMethods <> null and $parentClassMethods <> null) {
            $classMethods = array_diff($subClassMethods, $parentClassMethods);
        }
//		debug($parentClassMethods);

        if (count($classMethods) > 0) {
            $appActions = array();
            foreach ($classMethods as $value) {
                if (substr($value, 0, 2) == 'fn' OR substr($value, 0, 3) == '_fn') {
                    //empty because != OR != => cancels statement
                } else {
                    $appActions[$value] = $value;
                }
            }

            //DB
            $dbActions = $this->AdmAction->find('all', array(
                'recursive' => 0,
                'fields' => array('AdmAction.name'),
                'conditions' => array('AdmAction.adm_controller_id' => $controllerId),
                'order' => array('AdmAction.name' => 'ASC')
            ));
            $formatDbActions = array();
            foreach ($dbActions as $key => $value) {
                $formatDbActions[$key] = $value['AdmAction']['name'];
            }
            //debug(array_diff($appActions, $formatDbActions));
            //debug($formatDbActions);
            //debug($appActions);
            return array_diff($appActions, $formatDbActions);
        }
        return array();
    }

    //***************************** END CONTROLLERS ********************************
}
