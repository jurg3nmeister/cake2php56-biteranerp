<?php

/* (c)Bittion | Created: 15/01/2015 | Developer:reyro | Controller: SalOffices */

App::uses('AppController', 'Controller');

class SalOfficesController extends AppController
{

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 15/01/2015 | Developer: reyro | Description: Save (create and update)
    public function save()
    {
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalOffice->id = $this->passedArgs['id'];
            if (!$this->SalOffice->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalOffice->recursive = -1;
            $this->request->data = $this->SalOffice->read(null, $this->passedArgs['id']);
        }

    }

//    Created: 15/01/2015 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index()
    {

    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 15/01/2015 | Developer: reyro | Description: Function save (create and update) | Request: Ajax    
    public function fnSave()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data'];
            $data['SalOffice']['invoice_name'] = strtoupper($data['SalOffice']['invoice_name']);
            try {
                if ($this->SalOffice->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalOffice->id)));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 15/01/2015 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead()
    {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalOffice';
            $fields = array($model . '.name', $model . '.address', $model . '.city', $model . '.country', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.address) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.city) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.country) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
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
//            $total = $this->params['paging'][$model]['pageCount'];    //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['name'];
                $array['aaData'][$key][1] = $value[$model]['address'];
                $array['aaData'][$key][2] = $value[$model]['city'];
                $array['aaData'][$key][3] = $value[$model]['country'];
                $array['aaData'][$key][4] = $value[$model]['id']; //for edit and delete buttons
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 15/01/2015 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalOffice->id = $this->request->data['id'];
            try {
                if ($this->SalOffice->delete()) {
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
