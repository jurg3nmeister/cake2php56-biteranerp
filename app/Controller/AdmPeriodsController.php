<?php

/* (c)Bittion Admin Module | Created: 19/08/2014 | Developer:reyro */
App::uses('AppController', 'Controller');

class AdmPeriodsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 19/08/2014 | Developer: reyro | Description: View Create 
    public function create() {
        
    }


//    Created: 19/08/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

//    Created: 19/08/2014 | Developer: reyro | Description: View logic deleted periods
    public function deleted_periods() {
        
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 19/08/2014 | Developer: reyro | Description: Function Create | Request: Ajax
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmPeriod->create();
            try {
                if ($this->AdmPeriod->saveAssociated($this->request->data['data'])) {
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

//    Created: 19/08/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'AdmPeriod';
            $fields = array($model . '.id', $model . '.name', $model . '.active');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, 'AdmPeriod.lc_state !=' => 'LOGIC_DELETED');
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
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['active'];
                $array['aaData'][$key][2] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 19/08/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnReadDeletedPeriods() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'AdmPeriod';
            $fields = array($model . '.id', $model . '.name');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, 'AdmPeriod.lc_state' => 'LOGIC_DELETED');
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
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 19/08/2014 | Developer: reyro | Description: Function Logic Delete change lc_state to LOGIC_DELETED | Request: Ajax 	 	
    public function fnLogicDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            $this->AdmPeriod->id = $this->request->data['id'];
            $data['AdmPeriod']['lc_state'] = 'LOGIC_DELETED';
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmPeriod->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS',array('content'=>'DELETE'));
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
    public function fnActivate() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            $data['AdmPeriod']['id'] = $this->request->data['id'];
            $data['AdmPeriod']['active'] = $this->request->data['active'];
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmPeriod->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'ACTIVE'));
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
    public function fnRestore() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = array();
            $this->AdmPeriod->id = $this->request->data['id'];
            $data['AdmPeriod']['lc_state'] = 'ELABORATED';
            if ($this->request->data['id'] != '') {
                try {
                    if ($this->AdmPeriod->save($data)) {
                        $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'RESTORE'));
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

    //    Created: 07/08/2014 | Developer: reyro | Description: verify unique email
    public function fnVerifyUniquePeriod() {
        if ($this->RequestHandler->isAjax()) {
            $period = $this->request->data['value'];
            $response = $this->AdmPeriod->find('count', array(
                'conditions' => array('AdmPeriod.name' => $period),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

    //***************************** END CONTROLLERS ********************************
}
