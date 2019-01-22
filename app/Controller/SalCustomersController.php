<?php

/* (c)Bittion | Created: 22/09/2014 | Developer:reyro | Controller: SalCustomers */

App::uses('AppController', 'Controller');

class SalCustomersController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 22/09/2014 | Developer: reyro | Description: View Index related with fnRead()     
    public function index() {
        
    }

//    Created: 22/09/2014 | Developer: reyro | Description: create and update 
    public function save() {
        if (isset($this->passedArgs['id'])) {
            $this->SalCustomer->id = $this->passedArgs['id'];
            if (!$this->SalCustomer->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalCustomer->recursive = -1;
            $this->request->data = $this->SalCustomer->read(null, $this->passedArgs['id']);
        }
        $fillAutoComplete = $this->_fnFillAutoComplete();
        $this->set('sectors', $fillAutoComplete['sectors']);
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 22/09/2014 | Developer: reyro | Description: creates and updates
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->request->data['data']['SalCustomer']['sector'] = strtoupper($this->request->data['data']['SalCustomer']['sector']);
            $this->request->data['data']['SalCustomer']['name'] = strtoupper($this->request->data['data']['SalCustomer']['name']);
            $this->request->data['data']['SalCustomer']['nit_name'] = strtoupper($this->request->data['data']['SalCustomer']['nit_name']);
            try {
                if ($this->SalCustomer->save($this->request->data['data'])) {
                    $fillAutoComplete = $this->_fnFillAutoComplete();
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalCustomer->id, 'autocomplete' => $fillAutoComplete)));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/09/2014 | Developer: reyro | Description: creates and updates
    public function fnSaveCustomersEmployee() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            try {
                if ($this->SalCustomer->SalCustomersEmployee->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => 'esta')));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/09/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalCustomer';
            $fields = array($model . '.name', $model . '.nit', $model . '.phone', $model . '.phone2', $model . '.email', $model . '.sector', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.nit) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.phone) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.phone2) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.email) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.sector) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR /* ,conditions AND */);
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
//            $total = $this->params['paging'][$model]['pageCount']; //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['nit'];
                $array['aaData'][$key][2] = $value[$model]['phone'];
                $array['aaData'][$key][3] = $value[$model]['phone2'];
                $array['aaData'][$key][4] = $value[$model]['email'];
                $array['aaData'][$key][5] = $value[$model]['sector'];
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

    //    Created: 22/09/2014 | Developer: reyro | Description: Function Read for DataTable WITHOUT PAGINATION| Request: Ajax 	
    public function fnReadCustomersEmployee() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalCustomersEmployee';
            $fields = array($model . '.name', $model . '.phone', $model . '.email', $model . '.job_title', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.phone) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.email) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.job_title) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, $model . '.sal_customer_id' => $this->request->data['customerId']);
            /////////////////////////////////////
            $this->loadModel($model);
            $this->$model->recursive = -1;
//            $this->paginate = array(
//                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
////                'limit' => $this->request->data['iDisplayLength'],
////                'offset' => $this->request->data['iDisplayStart'],
//                'fields' => $fields,
//                'conditions' => $conditions
//            );
//            $arrayPaginate = $this->paginate($model);
           $arrayPaginate = $this->$model->find('all', array('order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']), 'fields' => $fields, 'conditions' => $conditions));
//            $this->loadModel($model);
//            $total = $this->$model->find('count', array(
//                'conditions' => $conditions
//            ));
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['phone'];
                $array['aaData'][$key][2] = $value[$model]['email'];
                $array['aaData'][$key][3] = $value[$model]['job_title'];
                $array['aaData'][$key][4] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
//            $array['iTotalRecords'] = $total;
//            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/09/2014 | Developer: reyro | Description: Function Delete Customers Employee| Request: Ajax 	
    public function fnDeleteCustomersEmployee() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');

//            $sales = $this->InvProduct->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.inv_product_id' => $this->request->data['id'])));
//            if ($sales > 0) {
//                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada', 'content' => 'No se puede eliminar, ya fue usado para VENTAS'));
//                return new CakeResponse(array('body' => json_encode($response)));
//            }
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalCustomer->SalCustomersEmployee->id = $this->request->data['id'];
            try {
                if ($this->SalCustomer->SalCustomersEmployee->delete()) {
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

//    Created: 22/09/2014 | Developer: reyro | Description: Function Delete Customer| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');

//            $sales = $this->InvProduct->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.inv_product_id' => $this->request->data['id'])));
//            if ($sales > 0) {
//                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada', 'content' => 'No se puede eliminar, ya fue usado para VENTAS'));
//                return new CakeResponse(array('body' => json_encode($response)));
//            }
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalCustomer->id = $this->request->data['id'];
            try {
                if ($this->SalCustomer->delete()) {
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

//    Created: 12/10/2014 | Developer: reyro | Description: verify unique nit
    public function fnVerifyUniqueNit() {
        if ($this->RequestHandler->isAjax()) {
            $nit = $this->request->data['value'];
            $id = '';
            if(isset($this->request->data['id'])){
                $id = $this->request->data['id'];
            }
            $response = $this->SalCustomer->find('count', array(
                'conditions' => array('SalCustomer.nit' => $nit, 'NOT'=>array('SalCustomer.id'=>$id)),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }      
    
///////////////////////PRIVATE FUNCTIONS
//    Created: 22/09/2014 | Developer: reyro | Description: Fills autocomplete inputs    
    private function _fnFillAutoComplete() {
        $sectors = $this->SalCustomer->find('list', array('fields' => array('SalCustomer.sector', 'SalCustomer.sector'), 'group' => array('SalCustomer.sector')));
        return array('sectors' => $sectors);
    }

//***************************** END CONTROLLERS ********************************    
}
