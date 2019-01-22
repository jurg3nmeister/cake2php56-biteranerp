<?php

/* (c)Bittion Admin Module | Created: 15/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmControllersController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 15/08/2014 | Developer: reyro | Description: View Create 
    public function create() {
        $modules = $this->AdmController->AdmModule->find('list', array('fields' => array('initials', 'name'), 'recursive' => -1, 'order' => array('AdmModule.id' => 'ASC')));
        $firstModuleInitials = strtolower(key($modules));
        $controllers = $this->_fnGetControllersAvailable($firstModuleInitials);
        $this->set(compact('modules', 'controllers'));
    }


//    Created: 15/08/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 15/08/2014 | Developer: reyro | Description: Function Create | Request: Ajax
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            //TRANSACTION CASE: All the Exceptions, Validations are done inside this MODEL
            $response = $this->AdmController->fnCreateControlWithLifeCycles($this->request->data['data']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

    //    Created: 15/08/2014 | Developer: reyro | Description: Function udpating one field by time with x-editable jquery plugin | Request: Ajax 	 	
    public function fnUpdateXEditable() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            if ($this->request->data['pk'] != '' && $this->request->data['name'] != '') { //Always send id inside $this->request->data
//                $this->AdmController->id = $this->request->data['pk']; //it doesn't work all the times
                $data['AdmController']['id'] = $this->request->data['pk'];
                $field = explode('-', $this->request->data['name']); //name is the id of <a><a/>, I'll use it to make this dinamycally with any other field not only descpription in this case
                $data['AdmController'][$field[0]] = $this->request->data['value'];
                try {
                    if ($this->AdmController->save($data)) {
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

//    Created: 15/08/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'AdmController';
            $fields = array($model . '.name', $model . '.description', 'AdmModule.name', $model . '.id');
            $conditionsOR = array(
                'replace(' . $model . '.name, \'_\', \'\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.description) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(AdmModule.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
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
                $array['aaData'][$key][0] = $value['AdmModule']['name'];
                $array['aaData'][$key][1] = Inflector::camelize($value[$model]['name']);
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

//    Created: 15/08/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmController->id = $this->request->data['id'];
            try {
                if ($this->AdmController->delete()) {
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

//    Created: 15/08/2014 | Developer: reyro | Description: get controllers available| Request: Ajax 	
    public function fnReadControllersAvaliable() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnGetControllersAvailable($this->request->data['moduleIntials']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

////////////////////////PRIVATE FUNCTIONS    
    private function _fnGetControllersAvailable($moduleIntials) {
        //1. Get all Cakephp controllers, except for plugins
        $cakeControllers = App::objects('controller');
        $cakeFormatedControllers = array();
        foreach ($cakeControllers as $cakeController) {
            if (strtolower(substr($cakeController, 0, 3)) == $moduleIntials) { //compare initials between cakeControllers and module
                $clean = substr($cakeController, 0, -10); //remove "Controller" word from behind
                $formatTrigger = strtolower(preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", "_", $clean)); //underscore every capital letter, al formato trigger Ex: AdmModules -> adm_modules
                $cakeFormatedControllers[$formatTrigger] = $clean;
            }
        }

        //2. Get DB values and format them to 
        $dbControllers = $this->AdmController->find('all', array('recursive' => 0, 'fields' => array('AdmController.name', 'AdmController.name'), 'conditions' => array('AdmModule.initials' => $moduleIntials)));
        $dbFormatedControllers = array();
        foreach ($dbControllers as $keyDbController => $dbController) {
            $dbFormatedControllers[$keyDbController] = Inflector::camelize($dbController['AdmController']['name']);
        }

        //3. Compare cakeControllers to dbControllers, only returns those not registered
        return array_diff($cakeFormatedControllers, $dbFormatedControllers);
    }

    //***************************** END CONTROLLERS ********************************
}
