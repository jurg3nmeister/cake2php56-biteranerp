<?php

/* (c)Bittion | Created: 17/10/2014 | Developer:reyro | Controller: SalInvoices */

App::uses('AppController', 'Controller');

class SalInvoicesController extends AppController
{

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 17/10/2014 | Developer: reyro | Description: Save (create and update)
    public function save()
    {
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalInvoice->id = $this->passedArgs['id'];
            if (!$this->SalInvoice->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalInvoice->recursive = -1;
            $this->request->data = $this->SalInvoice->read(null, $this->passedArgs['id']);
            $this->request->data['SalInvoice']['start_date'] = $this->BittionMain->fnGetFormatDate($this->request->data['SalInvoice']['start_date']);
            //To solve select doesn't recognize true false because select value is 1 & 0
            $active = 0;
            if ($this->request->data['SalInvoice']['active']) {
                $active = 1;
            }
            $this->request->data['SalInvoice']['active'] = $active;
        } else {
            //When CREATE to fill fields if already exists (save time)
            $this->request->data = $this->SalInvoice->find('first', array('order' => array('SalInvoice.id' => 'DESC')));
//        debug($this->request->data);
            if (count($this->request->data) > 0) {
                $this->request->data['SalInvoice']['authorization_number'] = '';
                $this->request->data['SalInvoice']['control_key'] = '';
                $this->request->data['SalInvoice']['start_date'] = '';
                $this->request->data['SalInvoice']['valid_days'] = '';
                $this->request->data['SalInvoice']['active'] = '';
            }
        }

//        if(count($this->request->data) == >)
        $booleans = array(1 => 'Si', 0 => 'No');
        $this->set(compact('booleans'));
    }

//    Created: 17/10/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index()
    {

    }

//    Created: 21/02/2015 | Developer: reyro | Description: Invoice activation
    public function activation()
    {

    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 17/10/2014 | Developer: reyro | Description: Function save (create and update) | Request: Ajax    
    public function fnSave()
    {
        if ($this->RequestHandler->isAjax()) {
//            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data'];
//            $data['SalInvoice']['finish_date'] = $this->BittionMain->fnSetFormatDate($data['SalInvoice']['finish_date']);

            //////////////////////Dates Handling - START //////////////////////////
            $startDate = str_replace('/', '-', $data['SalInvoice']['start_date']);  //example format: 31/01/2015
            $validDays = $data['SalInvoice']['valid_days'];
            $data['SalInvoice']['finish_date'] = date('d/m/Y', strtotime($startDate . ' +'.$validDays.' days')) ;

            $data['SalInvoice']['start_date'] = $this->BittionMain->fnSetFormatDate($data['SalInvoice']['start_date']);
            $data['SalInvoice']['finish_date'] = $this->BittionMain->fnSetFormatDate($data['SalInvoice']['finish_date']);
            /////////////////////Dates handling - END ////////////////////////////

            $data['SalInvoice']['tax_name'] = strtoupper($data['SalInvoice']['tax_name']);
            $data['SalInvoice']['legal_representative'] = strtoupper($data['SalInvoice']['legal_representative']);
            $data['SalInvoice']['main_activity'] = strtoupper($data['SalInvoice']['main_activity']);
//            $data['SalInvoice']['control_key'] = strtoupper($data['SalInvoice']['control_key']);

            $response = $this->SalInvoice->fnModelSave($data, $this->Session->read('Auth.User.id'));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 17/10/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead()
    {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalInvoice';
            $this->SalInvoice->virtualFields['formated_finish_date'] = 'TO_CHAR("SalInvoice"."finish_date", \'dd/mm/yyyy\')';
            $fields = array($model . '.tax_number', $model . '.tax_name', $model . '.authorization_number', 'SalInvoice.formated_finish_date', $model . '.active', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.tax_number) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.tax_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.authorization_number) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'TO_CHAR(' . $model . '.finish_date, \'dd/mm/yyyy\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
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
                $array['aaData'][$key][0] = $value[$model]['tax_number'];
                $array['aaData'][$key][1] = $value[$model]['tax_name'];
                $array['aaData'][$key][2] = $value[$model]['authorization_number'];
                $array['aaData'][$key][3] = $value[$model]['formated_finish_date'];
                $array['aaData'][$key][4] = $value[$model]['active'];
                $array['aaData'][$key][5] = $value[$model]['id']; //for edit and delete buttons
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 17/10/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalInvoice->id = $this->request->data['id'];
            try {
                if ($this->SalInvoice->delete()) {
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

//    Created: 17/10/2014 | Developer: reyro | Description: verify authorization number
    public function fnAuthorizationNumberUnique()
    {
        if ($this->RequestHandler->isAjax()) {
            $value = $this->request->data['value'];
            $id = '';
            if (isset($this->request->data['id'])) {
                $id = $this->request->data['id'];
            }
            $response = $this->SalInvoice->find('count', array(
                'conditions' => array('SalInvoice.authorization_number' => $value, 'NOT' => array('SalInvoice.id' => $id)),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 17/10/2014 | Developer: reyro | Description: verify control key
    public function fnControlKeyUnique()
    {
        if ($this->RequestHandler->isAjax()) {
            $value = $this->request->data['value'];
            $id = '';
            if (isset($this->request->data['id'])) {
                $id = $this->request->data['id'];
            }
            $response = $this->SalInvoice->find('count', array(
                'conditions' => array('SalInvoice.control_key' => $value, 'NOT' => array('SalInvoice.id' => $id)),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 21/02/2015 | Developer: reyro | Description: activate invoice
    public function fnActivateInvoice()
    {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $data = $this->request->data['data']['SalInvoice'];
            $controlCode = $this->fnGenerateInvoiceControlCode($data['authorization_number'],$data['invoice_number'],$data['nit'],$data['date'],$data['total'],$data['control_key']);
            $response = array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Código generado!', 'data' => array('controlCode' => $controlCode));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 21/02/2015 | Developer: reyro | Description: generates invoice control code
    public function fnGenerateInvoiceControlCode($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey)
    {
        if ($nit == '') {
            $nit = 0;
        }
        $arrayDate = explode('/', $invoiceDate); //d/m/Y
        $invoiceDate = $arrayDate[2] . $arrayDate[1] . $arrayDate[0]; //Ymd = inverted without spaces

        $finalTotalWithoutDecimals = explode('.', $finalTotal); //take off the decimals
        $finalTotal = $finalTotalWithoutDecimals[0];

        $dirVendors = App::path('Vendor'); //[0] is /app/Vendor and 1 is /vendors
        require_once $dirVendors[0] . 'CodigoControl.php';
        $obj = new CodigoControl($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey);
        $controlCode = $obj->generar();
        return $controlCode;
    }

///////////////////////PRIVATE FUNCTIONS
    //***************************** END CONTROLLERS ********************************
}
