<?php

/* (c)Bittion Admin Module | Created: 20/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmParametersController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 07/01/2015 | Developer: reyro | Description: Save (create and update)
    public function save() {
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->AdmParameter->id = $this->passedArgs['id'];  
            if (!$this->AdmParameter->exists()) {
                $this->redirect(array('action' => 'index'));
            }

            $this->AdmParameter->recursive = -1;
            $this->request->data = $this->AdmParameter->read(null, $this->passedArgs['id']);
        }
    }    
    
//    Created: 20/08/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 07/01/2015 | Developer: reyro | Description: Function save (create and update) | Request: Ajax
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data'];
            if($data['AdmParameter']['var_boolean'] == ''){
                $data['AdmParameter']['var_boolean'] = null;
            }
            try {
                if ($this->AdmParameter->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->AdmParameter->id)));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 20/08/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'AdmParameter';
            $fields = array($model . '.id', $model . '.parameter_key', $model . '.var_string_short', $model . '.var_string_long', $model . '.var_integer', $model . '.var_boolean', $model . '.var_decimal');
            $conditionsOR = array(
                'lower(' . $model . '.parameter_key) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
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
                $array['aaData'][$key][0] = $value[$model]['parameter_key'];
                $array['aaData'][$key][1] = $value[$model]['var_string_short'];
                $array['aaData'][$key][2] = $value[$model]['var_string_long'];
                $array['aaData'][$key][3] = $value[$model]['var_integer'];
                $array['aaData'][$key][4] = $value[$model]['var_boolean'];
                $array['aaData'][$key][5] = $value[$model]['var_decimal'];
                $array['aaData'][$key][6] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 20/08/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmParameter->id = $this->request->data['id'];
            try {
                if ($this->AdmParameter->delete()) {
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

    //***************************** END CONTROLLERS ********************************
}
