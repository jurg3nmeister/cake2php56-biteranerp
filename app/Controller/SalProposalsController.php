<?php

/* (c)Bittion | Created: 07/06/2015 | Developer:reyro | Controller: SalProposals */

App::uses('AppController', 'Controller');

class SalProposalsController extends AppController
{

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 07/06/2015 | Developer: reyro | Description: Save (create and update)
    public function save()
    {
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalProposal->id = $this->passedArgs['id'];
            if (!$this->SalProposal->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalProposal->recursive = -1;
            $this->request->data = $this->SalProposal->read(null, $this->passedArgs['id']);
        }
        $this->loadModel('SalCustomer');
        $customers = $this->SalCustomer->find('list');
        $types = array("publica"=>"publica", "privada"=>"privada", "extranjera"=>"extranjera");
        $lc_states = array("ELABORADA"=>"Elaborada", "ENVIADA"=>"Enviada", "RECIBIDA"=>"Recibida","APROBADA"=>"Aprobada", "RECHAZADA"=>"Rechazada");
        $this->set(compact('customers', 'types', 'lc_states'));
    }

//    Created: 07/06/2015 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index()
    {

    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 07/06/2015 | Developer: reyro | Description: Function save (create and update) | Request: Ajax    
    public function fnSave()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data'];
            $data['SalProposal']['sent_date'] = $this->BittionMain->fnSetFormatDate($data['SalProposal']['sent_date']);
            try {
                if ($this->SalProposal->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalProposal->id)));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead()
    {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalProposal';
            $this->SalProposal->virtualFields['date'] = 'TO_CHAR("SalProposal"."sent_date", \'dd/mm/yyyy\')';
            $fields = array($model . '.id', $model . '.name', $model . '.date', $model . '.type', $model . '.lc_state', 'SalCustomer.name');
            $conditionsOR = array(
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'TO_CHAR(' . $model . '.sent_date, \'dd/mm/yyyy\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.type) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.lc_state) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%'
            );
            $conditions = array('OR' => $conditionsOR /* ,conditions AND */);
            /////////////////////////////////////
//            $this->$model->recursive = -1;
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
                $array['aaData'][$key][1] = $value[$model]['date'];
                $array['aaData'][$key][2] = $value['SalCustomer']['name'];
                $array['aaData'][$key][3] = $value[$model]['type'];
                $array['aaData'][$key][4] = $value[$model]['lc_state'];
                $array['aaData'][$key][5] = $value[$model]['id']; //for edit and delete buttons
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalProposal->id = $this->request->data['id'];
            try {
                if ($this->SalProposal->delete()) {
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
