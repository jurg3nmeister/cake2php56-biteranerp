<?php

/* (c)Bittion | Created: 12/10/2014 | Developer:reyro | Controller: InvBrands */

App::uses('AppController', 'Controller');

class InvBrandsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 12/10/2014 | Developer: reyro | Description: Save (create and update)
    public function save() {
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->InvBrand->id = $this->passedArgs['id'];
            if (!$this->InvBrand->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->InvBrand->recursive = -1;
            $this->request->data = $this->InvBrand->read(null, $this->passedArgs['id']);
        }
    }

//    Created: 12/10/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 12/10/2014 | Developer: reyro | Description: Function save (create and update) | Request: Ajax    
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data'];
            try {
                if ($this->InvBrand->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->InvBrand->id)));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 12/10/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'InvBrand';
            $fields = array($model . '.name', $model . '.description', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.description) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, 'InvBrand.id !='=>4);
            /////////////////////////////////////
            $this->$model->recursive = -1;
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
//            $total = $this->params['paging'][$model]['pageCount'];    //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['description'];
                $array['aaData'][$key][2] = $value[$model]['id']; //for edit and delete buttons
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 12/10/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->InvBrand->id = $this->request->data['id'];
            try {
                if ($this->InvBrand->delete()) {
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

///////////////////////PRIVATE FUNCTIONS

    //***************************** END CONTROLLERS ********************************
}
