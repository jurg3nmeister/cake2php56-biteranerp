<?php

/* (c)Bittion | Created: 07/10/2014 | Developer:reyro | Controller: SalSales */

App::uses('AppController', 'Controller');

class SalSalesController extends AppController {

//*****************************START CONTROLLERS *******************************
////////////////////////VIEWS    
//    Created: 07/10/2014 | Developer: reyro | Description: View Index related with fnRead()     
    public function index() {
        
    }

//    Created: 05/06/2015 | Developer: reyro | Description: View Admin Index related with fnRead()
    public function admin_index() {

    }

//    Created: 07/10/2014 | Developer: reyro | Description: create and update 
    public function save() {
        $date = '';
        $systemCode = '';
        $customerNit = '';
        $customerNitName = '';
        $debt = '';
        $authorizationNumber = '';
        $invoiceNumber = '';
        $controlCode = '';

        $this->loadModel('SalOffice');
        $offices = $this->SalOffice->find('list', array('order'=>array('SalOffice.id'=>'ASC')));
        $this->loadModel('SalCustomer');
        $customers = $this->SalCustomer->find('list');
        $customerId = key($customers);

        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalSale->id = $this->passedArgs['id'];
            if (!$this->SalSale->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalSale->recursive = -1;
            $this->SalSale->unbindModel(array('hasMany' => array('SalSalesDetail')));

            $this->request->data = $this->SalSale->read(null, $this->passedArgs['id']);
            $systemCode = ': <STRONG>' . $this->request->data['SalSale']['system_code'] . '</STRONG>';
            $debt = $this->request->data['SalSale']['debt'];
            $date = $this->BittionMain->fnGetFormatDate($this->request->data['SalSale']['date']);

            $customerNit = $this->request->data['SalSale']['customer_nit'];
            $customerNitName = $this->request->data['SalSale']['customer_nit_name'];
            $authorizationNumber = $this->request->data['SalSale']['authorization_number'];
            $invoiceNumber = $this->request->data['SalSale']['invoice_number'];
            $controlCode = $this->request->data['SalSale']['control_code'];
        } else {//CREATE
            $date = date('d/m/Y');

            $customerData = $this->SalSale->SalCustomer->find('first', array('fields' => array('SalCustomer.nit', 'SalCustomer.nit_name'), 'conditions' => array('SalCustomer.id' => $customerId), 'recursive' => -1));
//            debug($customerData);
            if (count($customerData) > 0) {
                $customerNit = $customerData['SalCustomer']['nit'];
                $customerNitName = $customerData['SalCustomer']['nit_name'];
            }
        }

        $this->set(compact('customers', 'offices', 'date', 'systemCode', 'customerNit', 'customerNitName', 'debt', 'authorizationNumber', 'invoiceNumber', 'controlCode'));
    }

//    Created: 24/02/2015 | Developer: reyro | Description: create and update admin mode without limitations
    public function admin_save() {
        $date = '';
        $systemCode = '';
        $customerNit = '';
        $customerNitName = '';
        $debt = '';
        $authorizationNumber = '';
        $invoiceNumber = '';
        $controlCode = '';

        $this->loadModel('SalOffice');
        $offices = $this->SalOffice->find('list', array('order'=>array('SalOffice.id'=>'ASC')));
        $this->loadModel('SalCustomer');
        $customers = $this->SalCustomer->find('list');
        $customerId = key($customers);

        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalSale->id = $this->passedArgs['id'];
            if (!$this->SalSale->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalSale->recursive = -1;
            $this->SalSale->unbindModel(array('hasMany' => array('SalSalesDetail')));

            $this->request->data = $this->SalSale->read(null, $this->passedArgs['id']);
            $systemCode = ': <STRONG>' . $this->request->data['SalSale']['system_code'] . '</STRONG>';
            $debt = $this->request->data['SalSale']['debt'];
            $date = $this->BittionMain->fnGetFormatDate($this->request->data['SalSale']['date']);

            $customerNit = $this->request->data['SalSale']['customer_nit'];
            $customerNitName = $this->request->data['SalSale']['customer_nit_name'];
            $authorizationNumber = $this->request->data['SalSale']['authorization_number'];
            $invoiceNumber = $this->request->data['SalSale']['invoice_number'];
            $controlCode = $this->request->data['SalSale']['control_code'];
            $switchInvoice = 1;
            if($controlCode == ''){
                $switchInvoice = 0;
            }
        } else {//CREATE
            $date = date('d/m/Y');

            $customerData = $this->SalSale->SalCustomer->find('first', array('fields' => array('SalCustomer.nit', 'SalCustomer.nit_name'), 'conditions' => array('SalCustomer.id' => $customerId), 'recursive' => -1));
//            debug($customerData);
            if (count($customerData) > 0) {
                $customerNit = $customerData['SalCustomer']['nit'];
                $customerNitName = $customerData['SalCustomer']['nit_name'];
            }
        }

        $this->set(compact('switchInvoice', 'customers', 'offices', 'date', 'systemCode', 'customerNit', 'customerNitName', 'debt', 'authorizationNumber', 'invoiceNumber', 'controlCode'));
    }

//    Created: 07/10/2014 | Developer: reyro | Description: View Index related with fnRead()     
    public function invoice() {
        $this->layout = 'print';
        if (isset($this->passedArgs['id'])) {
//            $this->SalSale->id = $this->passedArgs['id']; //acted wierd
            $this->SalSale->unbindModel(array('belongsTo' => array('SalCustomer', 'AdmUser'), 'hasMany' => array('SalSalesDetail')));

            $this->request->data = $this->SalSale->find('first', array(
                'conditions' => array('SalSale.lc_state' => array('APPROVED', 'CANCELED'), 'SalSale.id' => $this->passedArgs['id']),
                'fields' => array('SalInvoice.tax_name','SalInvoice.tax_number', 'SalInvoice.main_activity', 'SalInvoice.description', 'SalInvoice.finish_date', 'SalOffice.invoice_name', 'SalOffice.address', 'SalOffice.phone', 'SalOffice.city', 'SalOffice.country', 'SalOffice.website', 'SalSale.invoice_number', 'SalSale.authorization_number', 'SalSale.date', 'SalSale.customer_nit', 'SalSale.customer_nit_name', 'SalSale.lc_state', 'SalSale.discount', 'SalSale.control_code')
            ));
//            debug($this->request->data);
//            if (count($this->request->data) == 0) {
//                $this->redirect(array('action' => 'index'));
//            }

            if (!isset($this->request->data['SalSale']['control_code'])) {
                $this->redirect(array('action' => 'index'));
            }
//////////////////////////////////////////////////////////////////////////////////////////////
            $this->SalSale->SalSalesDetail->unbindModel(array('belongsTo' => array('SalSales')));
            $this->SalSale->SalSalesDetail->virtualFields['subtotal'] = '(SELECT COALESCE(SUM(quantity * sale_price), 0) FROM sal_sales_details WHERE id = "SalSalesDetail"."id")';
            $SalSalesDetail = $this->SalSale->SalSalesDetail->find('all', array(
                'conditions' => array('SalSale.lc_state' => array('APPROVED', 'CANCELED'), 'SalSalesDetail.sal_sale_id' => $this->passedArgs['id']),
                'fields' => array('SalSalesDetail.sale_price', 'SalSalesDetail.quantity', 'SalSalesDetail.invoice_alternative_name', 'SalSalesDetail.subtotal', 'InvProduct.code', 'InvProduct.name', 'InvProduct.description', 'InvProduct.service')
            ));

            $this->set('SalSalesDetail', $SalSalesDetail);
/////////////////////////////////////////////////////////////////////////////////////////////            
            $this->request->data['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->BittionMain->fnGetFormatDate($this->request->data['SalSale']['date']));
            $this->request->data['SalInvoice']['finish_date'] = $this->BittionMain->fnGetFormatDate($this->request->data['SalInvoice']['finish_date']);
/////////////////////////////////////////////////////////////////////////////////////////////
            $totals = $this->SalSale->fnGetTotalsAndDiscount($this->passedArgs['id'], $this->request->data['SalSale']['discount']);
            $this->set('totals', $totals);
            
            ///////////////////////////number to word converter from app/Vendor
            $finalTotalWithoutDecimals = explode('.', number_format($totals['totalFinal'], 2, '.', ''));
//            debug($finalTotalWithoutDecimals);
            $dirVendors = App::path('Vendor'); //[0] is /app/Vendor and 1 is /vendors
            require_once $dirVendors[0] . 'NumberToWordConverter.php';
            $obj = new NumberToWordConverter;
            $this->set('totalFinalToWord', $obj->numberToWord($finalTotalWithoutDecimals[0]). ' ' . $finalTotalWithoutDecimals[1].'/100 '.'Bolivianos');


//            $date = $this->BittionMain->fnGetFormatDate($this->request->data['SalSale']['date']);
//            $this->set('date', $date)
            ////////////////////////Testing Vendor/CodigoControl
//            debug($this->SalSale->fnGenerateInvoiceControlCode($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey));
//            debug($this->SalSale->fnGenerateInvoiceControlCode(2001004296055, 1, 123456, '17/10/2014', 15393, 435435435435));
//            debug($this->SalSale->fnGenerateInvoiceControlCode('8004005263848', '673173', '1666188', '08/10/2008', '12', 'PNRU4cgz7if)[tr#J69j=yCS57i=uVZ$n@nv6wxaRFP+AUf*L7Adiq3TT[Hw-@wt'));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

    //    Created: 29/06/2015 | Developer: reyro | Description: View show generated sales report
    public function sale_report()
    {
        $this->layout = 'print';
        if(count($this->passedArgs) == 0){
            $this->redirect(array('action' => 'sale_report_generator'));
        }

        $startDate = $this->passedArgs['sD'].'/'.$this->passedArgs['sM'].'/'.$this->passedArgs['sY'];
        $endDate = $this->passedArgs['eD'].'/'.$this->passedArgs['eM'].'/'.$this->passedArgs['eY'];

        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);


        $startDate = $this->BittionMain->fnSetUnixFormatDate($startDate);
        $endDate = $this->BittionMain->fnSetUnixFormatDate($endDate);

        $query=$this->SalSale->SalSalesDetail->find('all', array(
            'fields'=>array('InvProduct.id', 'InvProduct.code', 'InvProduct.name', 'SalSalesDetail.id', 'SalSalesDetail.quantity', 'SalSalesDetail.price', 'SalSale.discount', 'SalSale.system_code')
        ,'conditions'=>array('SalSale.debt'=>0, 'SalSale.lc_state'=>'APPROVED',  array('SalSale.date <= ' => $endDate, 'SalSale.date >= ' => $startDate)
            )
        ));

        //1st foreach format
        $array = array();
        foreach($query as $value){
            $SalSalesDetailID = $value['SalSalesDetail']['id'];
            $subtotal = $value['SalSalesDetail']['price'] * $value['SalSalesDetail']['quantity'];
            if($value['SalSale']['discount'] != null){
                $subtotal = $subtotal - (($subtotal * $value['SalSale']['discount'])/100);
            }

            $array[$SalSalesDetailID]['quantity'] = $value['SalSalesDetail']['quantity'];
            $array[$SalSalesDetailID]['subtotal'] = $subtotal;
            $array[$SalSalesDetailID]['name'] = $value['InvProduct']['name'];
            $array[$SalSalesDetailID]['system_code'] = $value['SalSale']['system_code'];
            $array[$SalSalesDetailID]['product_id'] = $value['InvProduct']['id'];
            $array[$SalSalesDetailID]['product_code'] = $value['InvProduct']['code'];
        }

//        debug($array);
        //2nd foreach sum
        $report = array();

        $total = 0;
        $totalQuantity = 0;

        foreach($array as $value){
            $productId = $value['product_id'];
            if(isset($report[$productId]['quantity'])){
                $report[$productId]['quantity'] = $report[$productId]['quantity'] + $value['quantity'];
            }else{
                $report[$productId]['quantity'] = $value['quantity'];
            }
            if(isset($report[$productId]['subtotal'])){
                $report[$productId]['subtotal'] = $report[$productId]['subtotal'] + $value['subtotal'];
            }else{
                $report[$productId]['subtotal'] = $value['subtotal'];
                $report[$productId]['product'] = $value['name'];
                $report[$productId]['product_code'] = $value['product_code'];
            }
            $total = $total + $value['subtotal'];
            $totalQuantity = $totalQuantity + $value['quantity'];
        }

        $this->set('report', $report);
        $this->set('total', $total);
        $this->set('totalQuantity', $totalQuantity);


    }

//    Created: 29/06/2015 | Developer: reyro | Description: View generate sales report
    public function sale_report_generator(){
        if ($this->request->is('post')){
            //Save logic here

            list($startDay, $startMonth, $startYear) = split("/", $this->request->data['SalSale']['startDate']);
            list($endDay, $endMonth, $endYear) = split("/", $this->request->data['SalSale']['endDate']);
            //check if the same year
            if($startYear != $endYear){
                $this->Session->setFlash('Tiene que usar el mismo año en ambas fechas', 'default', array('class' => 'flashGrowlError'), 'flashGrowl');
                $this->redirect(array('action' => 'sale_report_generator'));
            }
            //Check if older with _fnGetIntervalDays
            if($this->_fnGetIntervalDays($this->request->data['SalSale']['startDate'], $this->request->data['SalSale']['endDate']) != 'VIGENTE'){
                $this->Session->setFlash('La Fecha Fin no puede ser mayor a la Fecha Inicio', 'default', array('class' => 'flashGrowlError'), 'flashGrowl');
                $this->redirect(array('action' => 'sale_report_generator'));
            }
            $this->redirect(array('controller'=>'SalSales','action' => 'sale_report', 'sD'=>$startDay, 'sM'=>$startMonth, 'sY'=>$startYear, 'eD'=>$endDay, 'eM'=>$endMonth, 'eY'=>$endYear));
        }
    }

////////////////////////PUBLIC FUNCTIONS    
//    Created: 07/10/2014 | Developer: reyro | Description: creates and updates
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////ONLY FOR CREATE
            $systemCode = '';
            if ($this->request->data['data']['SalSale']['id'] == '') {
                $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
                if ($currentExrate == '') {
                    return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'Error al obtener el cambio de moneda')))));
                }
                $this->request->data['data']['SalSale']['ex_rate'] = $currentExrate;
                $systemCode = $this->CurrentApp->fnGenerateCode('VEN', 'SalSale');
                $this->request->data['data']['SalSale']['system_code'] = $systemCode;
                unset($this->request->data['data']['SalSale']['lc_state']);
            }
            /////////////////
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $this->request->data['data']['SalSale']['adm_user_id'] = $this->Session->read('Auth.User.id');
//            if (isset($this->request->data['data']['SalSale']['date'])) { //The js will do validation, and also the DB, but is not quite good, need to do another function or something for the erp
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);
//            }
            ///////////////////////////////////////////////////////////////////

            $this->request->data['data']['SalSale']['paid'] = '0.00';
            $this->request->data['data']['SalSale']['debt'] = '0.00';
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalSale->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalSale->id, 'system_code' => $systemCode)));
                } else {
                    $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'No se pudo guardar', 'data' => $this->request->data['data']));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/10/2014 | Developer: reyro | Description: creates and updates
    public function fnAdminSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////ONLY FOR CREATE
            $systemCode = '';
            if ($this->request->data['data']['SalSale']['id'] == '') {
                $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
                if ($currentExrate == '') {
                    return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'Error al obtener el cambio de moneda')))));
                }
                $this->request->data['data']['SalSale']['ex_rate'] = $currentExrate;
                $systemCode = $this->CurrentApp->fnGenerateCode('VEN', 'SalSale');
                $this->request->data['data']['SalSale']['system_code'] = $systemCode;
                unset($this->request->data['data']['SalSale']['lc_state']);
            }
            /////////////////
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $this->request->data['data']['SalSale']['adm_user_id'] = $this->Session->read('Auth.User.id');
//            if (isset($this->request->data['data']['SalSale']['date'])) { //The js will do validation, and also the DB, but is not quite good, need to do another function or something for the erp
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);
//            }
            ///////////////////////////////////////////////////////////////////
            if($this->request->data['data']['SalSale']['paid'] == ''){
                $this->request->data['data']['SalSale']['paid'] = '0.00';
            }

            $this->request->data['data']['SalSale']['debt'] = '0.00';
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalSale->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalSale->id, 'system_code' => $systemCode)));
                } else {
                    $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'No se pudo guardar', 'data' => $this->request->data['data']));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 05/06/2015 | Developer: reyro | Description: generates invoice
    public function fnAdminGenerateInvoice() {
        if ($this->RequestHandler->isAjax()) {
            $count = $this->SalSale->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['data']['SalSale']['id'])));
            if ($count == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Facturación denegada!', 'content' => 'Debe existir al menos un producto en detalle'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
//            $this->request->data['data']['SalSale']['lc_state'] = 'APPROVED';
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $dateWithoutFormat = $this->request->data['data']['SalSale']['date'];
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);

            $response = $this->SalSale->fnModelAdminGenerateInvoice($this->request->data['data'], $dateWithoutFormat); //Validation inside the Model function
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/10/2014 | Developer: reyro | Description: creates and updates
    public function fnSaveSaleAndDetail() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////ONLY FOR CREATE
            $systemCode = '';
            if ($this->request->data['data']['SalSale']['id'] == '') {
                $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
                if ($currentExrate == '') {
                    return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'Error al obtener el cambio de moneda')))));
                }
                $this->request->data['data']['SalSale']['ex_rate'] = $currentExrate;
                $systemCode = $this->CurrentApp->fnGenerateCode('VEN', 'SalSale');
                $this->request->data['data']['SalSale']['system_code'] = $systemCode;
                unset($this->request->data['data']['SalSale']['lc_state']);
            }
            /////////////////
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $this->request->data['data']['SalSale']['adm_user_id'] = $this->Session->read('Auth.User.id');
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);

            $this->request->data['data']['SalSale']['paid'] = 0;
            $this->request->data['data']['SalSale']['debt'] = 0;
            ///////////////////////////////////////////////////////////////////////////////////
//            $this->request->data['data2']['data']['SalSalesDetail']['subtotal'] = $this->request->data['data2']['data']['SalSalesDetail']['sale_price'] * $this->request->data['data2']['data']['SalSalesDetail']['quantity'];
            $this->request->data['data']['SalSalesDetail'][0] = $this->request->data['data2']['data']['SalSalesDetail'];
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalSale->saveAssociated($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalSale->id, 'system_code' => $systemCode)));
                } else {
                    $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'No se pudo guardar', 'data' => $this->request->data['data']));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }

            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/10/2014 | Developer: reyro | Description: updates only when sale has been approved
    public function fnSaveWhenApproved() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////////////////////////////////////////
            $this->loadModel('SalSalesDetail');
            $total = $this->SalSalesDetail->find('first', array(
                'fields' => array('SUM("SalSalesDetail"."quantity" * "SalSalesDetail"."sale_price") AS "total"'),
                'conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['data']['SalSale']['id'])
            ));
            $discount = $this->SalSale->find('first', array('fields' => array('SalSale.discount'), 'recursive' => -1, 'conditions' => array('SalSale.id' => $this->request->data['data']['SalSale']['id'])));

            $paid = $this->request->data['data']['SalSale']['paid'];
            $discount = ($total[0]['total'] * $discount['SalSale']['discount']) / 100;
            $totalFinal = $total[0]['total'] - number_format($discount, 2, '.', '');
//            debug($totalFinal);
            if ($paid > $totalFinal) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada', 'content' => 'Lo PAGADO no puede ser mayor al TOTAL FINAL'));
                return new CakeResponse(array('body' => json_encode($response)));
            }

            $debt = number_format($totalFinal - $paid, 2, '.', '');
            $this->request->data['data']['SalSale']['debt'] = $debt;
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalSale->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('paid' => $paid, 'debt' => $debt, 'totalfinal' => $totalFinal)));
                } else {
                    $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'No se pudo guardar', 'data' => $this->request->data['data']));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

// Created: 07/10/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalSale';
            //Virtual field subquery (witouth this can't sort
            $this->SalSale->virtualFields['total'] = '(SELECT COALESCE(SUM(quantity * sale_price), 0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id")';
//            $this->SalSale->virtualFields['total_quantity'] = '(SELECT COALESCE(SUM(quantity), 0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id")';
            $this->SalSale->virtualFields['sale_date'] = 'TO_CHAR("SalSale"."date", \'dd/mm/yyyy\')';

            $fields = array($model . '.system_code'
                , 'SalSale.sale_date'
//                , 'SalSale.invoice_number'
                , 'SalCustomer.name'
                , 'SalCustomer.nit'
//                , $model . '.person_requesting'
//                , 'SalSale.total_quantity'
                , 'SalSale.total'
                , $model . '.id'
                , $model . '.lc_state'
                , 'SalOffice.invoice_name'
            );
            $conditionsOR = array(
                'lower(' . $model . '.system_code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'TO_CHAR(' . $model . '.date, \'dd/mm/yyyy\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'CAST(' . $model . '.invoice_number AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.nit) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower(' . $model . '.person_requesting) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalOffice.invoice_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'CAST((SELECT COALESCE(SUM(quantity),0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST((SELECT COALESCE(SUM(quantity * sale_price),0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR /*, 'SalSale.control_code !='=>null*/);
            /////////////////////////////////////
//            $this->$model->recursive = -1;
            $this->$model->unbindModel(array(
                'hasMany' => array('SalSalesDetail'),
                'belongsTo' => array('SalInvoice')
            ));
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
//            $log = $this->$model->getDataSource()->getLog(false, false);
//            debug($log);
            $total = $this->$model->find('count', array(
                'conditions' => $conditions
            ));
//            $total = $this->params['paging'][$model]['pageCount']; //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['system_code'];
                $array['aaData'][$key][1] = $value[$model]['sale_date']; //virtual field
//                $array['aaData'][$key][2] = $value[$model]['invoice_number'];
                $array['aaData'][$key][2] = $value['SalCustomer']['name'];
                $array['aaData'][$key][3] = $value['SalCustomer']['nit'];
//                $array['aaData'][$key][5] = $value[$model]['person_requesting'];
                $array['aaData'][$key][4] = $value['SalOffice']['invoice_name'];
//                $array['aaData'][$key][4] = $value[$model]['total_quantity']; //virtual field
                $array['aaData'][$key][5] = number_format($value[$model]['total'], 2, '.', ''); //virtual field
                $array['aaData'][$key][6] = array('id' => $value[$model]['id'], 'lc_state' => $value[$model]['lc_state']); //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;


            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

// Created: 07/10/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax
    public function fnAdminRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalSale';
            //Virtual field subquery (witouth this can't sort
            $this->SalSale->virtualFields['total'] = '(SELECT COALESCE(SUM(quantity * sale_price), 0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id")';
//            $this->SalSale->virtualFields['total_quantity'] = '(SELECT COALESCE(SUM(quantity), 0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id")';
            $this->SalSale->virtualFields['sale_date'] = 'TO_CHAR("SalSale"."date", \'dd/mm/yyyy\')';

            $fields = array($model . '.system_code'
            , 'SalSale.sale_date'
//                , 'SalSale.invoice_number'
            , 'SalCustomer.name'
            , 'SalCustomer.nit'
//                , $model . '.person_requesting'
//                , 'SalSale.total_quantity'
            , 'SalSale.total'
            , $model . '.id'
            , $model . '.lc_state'
            , 'SalOffice.invoice_name'
            , $model.'.control_code'
            );
            $conditionsOR = array(
                'lower(' . $model . '.system_code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'TO_CHAR(' . $model . '.date, \'dd/mm/yyyy\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'CAST(' . $model . '.invoice_number AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.nit) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower(' . $model . '.person_requesting) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalOffice.invoice_name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'CAST((SELECT COALESCE(SUM(quantity),0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST((SELECT COALESCE(SUM(quantity * sale_price),0) FROM sal_sales_details WHERE sal_sale_id = "SalSale"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR /* ,conditions AND */);
            /////////////////////////////////////
//            $this->$model->recursive = -1;
            $this->$model->unbindModel(array(
                'hasMany' => array('SalSalesDetail'),
                'belongsTo' => array('SalInvoice')
            ));
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
//            $log = $this->$model->getDataSource()->getLog(false, false);
//            debug($log);
            $total = $this->$model->find('count', array(
                'conditions' => $conditions
            ));
//            $total = $this->params['paging'][$model]['pageCount']; //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['system_code'];
                $array['aaData'][$key][1] = $value[$model]['sale_date']; //virtual field
//                $array['aaData'][$key][2] = $value[$model]['invoice_number'];
                $array['aaData'][$key][2] = $value['SalCustomer']['name'];
                $array['aaData'][$key][3] = $value['SalCustomer']['nit'];
//                $array['aaData'][$key][5] = $value[$model]['person_requesting'];
                $array['aaData'][$key][4] = $value['SalOffice']['invoice_name'];
//                $array['aaData'][$key][4] = $value[$model]['total_quantity']; //virtual field
                $array['aaData'][$key][5] = number_format($value[$model]['total'], 2, '.', ''); //virtual field
                $array['aaData'][$key][6] = array('id' => $value[$model]['id'], 'lc_state' => $value[$model]['lc_state'], 'control_code'=>$value[$model]['control_code']); //for edit and delete buttons, and check if there is invoice or not

//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;


            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 08/10/2014 | Developer: reyro | Description: Approve a sale| Request: Ajax
    public function fnApproveSale() {
        if ($this->RequestHandler->isAjax()) {
//            $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'Venta aprobada!'));
            $count = $this->SalSale->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['data']['SalSale']['id'])));
            if ($count == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Venta denegada!', 'content' => 'Debe existir al menos un producto en detalle'));
                return new CakeResponse(array('body' => json_encode($response)));
            }
            $this->request->data['data']['SalSale']['lc_state'] = 'APPROVED';
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $dateWithoutFormat = $this->request->data['data']['SalSale']['date'];
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);

            $response = $this->SalSale->fnModelApproveSale($this->request->data['data'], $dateWithoutFormat); //Validation inside the Model function
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 04/06/2015 | Developer: reyro | Description: Approve a sale without invoice| Request: Ajax
    public function fnAdminApproveSale() {
        if ($this->RequestHandler->isAjax()) {
//            $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'Venta aprobada!'));
            $count = $this->SalSale->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['data']['SalSale']['id'])));
            if ($count == 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Venta denegada!', 'content' => 'Debe existir al menos un producto en detalle'));
                return new CakeResponse(array('body' => json_encode($response)));
            }

            $this->request->data['data']['SalSale']['lc_state'] = 'APPROVED';
            $this->request->data['data']['SalSale']['customer_nit'] = $this->request->data['customerNit'];
            $this->request->data['data']['SalSale']['customer_nit_name'] = $this->request->data['customerNitName'];
            $dateWithoutFormat = $this->request->data['data']['SalSale']['date'];
            $this->request->data['data']['SalSale']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalSale']['date']);

            $response = $this->SalSale->fnModelAdminApproveSale($this->request->data['data'], $dateWithoutFormat); //Validation inside the Model function
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 08/10/2014 | Developer: reyro | Description: Cancel a sale| Request: Ajax 	
    public function fnCancelSale() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'Venta anulada!'));
            $this->request->data['lc_state'] = 'CANCELED';
            try {
                if (!$this->SalSale->save($this->request->data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'No se pudo anular la venta', 'data' => $this->request->data['data']));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/10/2014 | Developer: reyro | Description: Function Delete Customer| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->SalSale->fnDeleteSaleAndDetail($this->request->data['id']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/10/2014 | Developer: reyro | Description: Function Delete Customer from inside the document| Request: Ajax | Obs: session flash message on index redirect 	
    public function fnDeleteInside() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->SalSale->fnDeleteSaleAndDetail($this->request->data['id']);
            $systemCode = trim(str_replace("", ":", $this->request->data['systemCode']));
            if ($response['status'] == 'SUCCESS') {
                $this->Session->setFlash('Se eliminó permanentemente la venta: ' . $systemCode, 'default', array('class' => 'flashGrowlSuccess'), 'flashGrowl');
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Function Delete Customers Employee| Request: Ajax 	
    public function fnDeleteSalesDetails() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->loadModel('SalSalesDetail');
            $this->SalSalesDetail->id = $this->request->data['id'];
            try {
                if ($this->SalSalesDetail->delete()) {
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

//    Created: 24/09/2014 | Developer: reyro | Description: Read customers employees| Request: Ajax 	
    public function fnReadCustomerEmployees() {
        if ($this->RequestHandler->isAjax()) {
            $this->loadModel('SalCustomersEmployee');
            try {
                $customersEmployees = $this->SalCustomersEmployee->find('list', array('conditions' => array('SalCustomersEmployee.sal_customer_id' => $this->request->data['id']), 'fields' => array('SalCustomersEmployee.name', 'SalCustomersEmployee.name')));
                $response = array('customersEmployees' => $customersEmployees);
                $this->loadModel('SalCustomer');
//                $nit = $this->SalCustomer->find('list', array('fields'=>array('SalCustomer.nit', 'SalCustomer.nit_name', 'conditions'=>array('SalCustomer.id'=>$this->request->data['id']))));
                $nit = $this->SalCustomer->find('list', array('fields' => array('SalCustomer.nit', 'SalCustomer.nit_name'), 'conditions' => array('SalCustomer.id' => $this->request->data['id'])));
                foreach ($nit as $key => $value) {
                    $response['nit'] = $key;
                    $response['nitName'] = $value;
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 24/09/2014 | Developer: reyro | Description: Read products by Offer| Request: Ajax 	
    public function fnReadProducts() {
        if ($this->RequestHandler->isAjax()) {
            $conditions = null;
            if ($this->request->data['id'] != '') { //EXISTS
                $this->loadModel('SalSalesDetail');
                $productDetailIds = $this->SalSalesDetail->find('list', array(
                    'fields' => array('SalSalesDetail.inv_product_id', 'SalSalesDetail.inv_product_id'),
                    'conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['id'])
                ));
                $conditions = array('NOT' => array('InvProduct.id' => $productDetailIds));
            }
            try {
                $this->loadModel('InvProduct');
                $products = $this->InvProduct->find('list', array('conditions' => $conditions));
                $response = array('products' => $products);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 24/09/2014 | Developer: reyro | Description: Read product price| Request: Ajax 	
    public function fnReadProductPrices() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('InvProduct');
                $lastPrice = $this->InvProduct->InvPrice->find('first', array('conditions' => array('InvPrice.inv_product_id' => $this->request->data['productId']), 'order' => array('InvPrice.id' => 'DESC'), 'fields' => array('InvPrice.price')));
                $response = array('lastPrice' => $lastPrice['InvPrice']['price']);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Read product total| Request: Ajax 	
    public function fnReadTotal() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('SalSalesDetail');
                $total = $this->SalSalesDetail->find('first', array(
                    'fields' => array('SUM("SalSalesDetail"."quantity" * "SalSalesDetail"."sale_price") AS "total"'),
                    'conditions' => array('SalSalesDetail.sal_sale_id' => $this->request->data['id'])
                ));
                $response = array('total' => $total[0]['total']);

                $sale = $this->SalSale->find('first', array('fields' => array('SalSale.discount', 'SalSale.paid', 'SalSale.debt'), 'conditions' => array('SalSale.id' => $this->request->data['id'])));
//                $debt = number_format($totalFinal - $paid, 2, '.', '');
                $response['discount'] = '0.00';
                $response['totalAndDiscount'] = $total[0]['total'];
                $response['paid'] = $sale['SalSale']['paid'];
                $response['debt'] = $sale['SalSale']['debt'];
                if ($sale['SalSale']['discount'] != '') {
                    $response['discount'] = ($total[0]['total'] * $sale['SalSale']['discount']) / 100;
                    $response['discount'] = number_format($response['discount'], 2, '.', '');
                    $response['totalAndDiscount'] = $response['total'] - $response['discount'];
                    $response['totalAndDiscount'] = number_format($response['totalAndDiscount'], 2, '.', '');
                }
                if ($response['totalAndDiscount'] == null) {
                    $response['totalAndDiscount'] = '0.00';
                }
                $response['total'] = number_format($response['total'], 2, '.', '');
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Read product total| Request: Ajax 	
    public function fnReadSaleDetailUpdate() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('SalSalesDetail');
                $saleDetail = $this->SalSalesDetail->find('first', array(
                    'fields' => array('SalSalesDetail.inv_product_id', 'SalSalesDetail.price', 'SalSalesDetail.sale_price', 'SalSalesDetail.quantity', 'SalSalesDetail.invoice_alternative_name', 'InvProduct.name', 'InvProduct.code'),
                    'conditions' => array('SalSalesDetail.id' => $this->request->data['id'])
                ));
                $response = array($saleDetail);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Function Read Offer details for DataTable WITHOUT PAGINATION| Request: Ajax 	
    public function fnReadSalesDetail() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalSalesDetail';
            //Virtual field subquery (witouth this can't sort
            $this->loadModel($model);
            $this->SalSalesDetail->virtualFields['subtotal'] = '(SELECT SUM(quantity * sale_price) FROM sal_sales_details WHERE id = "SalSalesDetail"."id")';

            $fields = array('InvProduct.code', 'InvProduct.name', /*'InvProduct.measure',*/ $model . '.sale_price', $model . '.quantity', 'SalSalesDetail.subtotal', 'SalSalesDetail.invoice_alternative_name', $model . '.id');
            $conditionsOR = array(
                'lower(InvProduct.code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(InvProduct.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.sale_price AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.quantity AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower(InvProduct.measure) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, $model . '.sal_sale_id' => $this->request->data['id']);
            /////////////////////////////////////
            $this->loadModel($model);
            $arrayPaginate = $this->$model->find('all', array('order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']), 'fields' => $fields, 'conditions' => $conditions));
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $counter;
                $array['aaData'][$key][1] = $value['InvProduct']['code'];
                $array['aaData'][$key][2] = $value['InvProduct']['name'];
//                $array['aaData'][$key][3] = $value['InvProduct']['measure'];
                $array['aaData'][$key][3] = number_format($value[$model]['sale_price'], 2, '.', '');
                $array['aaData'][$key][4] = $value[$model]['quantity'];
                $array['aaData'][$key][5] = number_format($value[$model]['subtotal'], 2, '.', '');
                $array['aaData'][$key][6] = $value[$model]['id']; //for edit and delete buttons
                $counter++;

                $array['aaData'][$key][7] = $value[$model]['invoice_alternative_name'];
            }
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

///////////////////////PRIVATE FUNCTIONS

//    Created: 08/04/2015 | Developer: reyro | Description: Check difference between today and payment_deadline
    private function _fnGetIntervalDays($todayDate, $deadlineDate)
    {
        //Date format must be dd/mm/yyyy
        list($todayDay, $todayMonth, $todayYear) = split("/", $todayDate);
        list($deadlineDay, $deadlineMonth, $deadlineYear) = split("/", $deadlineDate);

        if ($todayYear <= $deadlineYear) {
            if($todayMonth == $deadlineMonth){
                if($todayDay == $deadlineDay){
//                    return 'FINALIZADO d t=d';
                    return 'FINALIZADO';
                }elseif($todayDay > $deadlineDay){
//                    return 'FINALIZADO d t>d';
                    return 'FINALIZADO';
                }elseif($todayDay < $deadlineDay){
//                    return 'VIGENTE d t<-d';
                    return 'VIGENTE';
                }
            }elseif($todayMonth > $deadlineMonth){
//                return 'FINALIZADO M t->d';
                return 'FINALIZADO';
            }elseif($todayMonth < $deadlineMonth){
//                return 'VIGENTE M t<d';
                return 'VIGENTE';
            }
        } else {
//            return 'FINALIZADO Y t->d';
            return "FINALIZADO";
        }
    }

//***************************** END CONTROLLERS ********************************    
}
