<?php

/* (c)Bittion | Created: 23/09/2014 | Developer:reyro | Controller: SalOffers */

App::uses('AppController', 'Controller');

class SalOffersController extends AppController {

//*****************************START CONTROLLERS *******************************
////////////////////////VIEWS    
//    Created: 23/09/2014 | Developer: reyro | Description: View Index related with fnRead()     
    public function index() {
        
    }

//    Created: 23/09/2014 | Developer: reyro | Description: create and update 
    public function save() {
        $date = '';
        $systemCode = '';
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->SalOffer->id = $this->passedArgs['id'];
            if (!$this->SalOffer->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->SalOffer->recursive = -1;
            $this->request->data = $this->SalOffer->read(null, $this->passedArgs['id']);
            $systemCode = ': <STRONG>' . $this->request->data['SalOffer']['system_code'] . '</STRONG>';
            $date = $this->BittionMain->fnGetFormatDate($this->request->data['SalOffer']['date']);
        } else {//CREATE
            $date = date('d/m/Y');
        }
        $this->loadModel('SalCustomer');
        $customers = $this->SalCustomer->find('list');
        $this->set(compact('customers', 'date', 'systemCode'));

        $this->SalOffer->SalOffersDetail->find('all');
    }

////////////////////////PUBLIC FUNCTIONS    
//    Created: 23/09/2014 | Developer: reyro | Description: creates and updates
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////ONLY FOR CREATE
            $systemCode = '';
            if ($this->request->data['data']['SalOffer']['id'] == '') {
                $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
                if ($currentExrate == '') {
                    return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'Error al obtener el cambio de moneda')))));
                }
                $this->request->data['data']['SalOffer']['ex_rate'] = $currentExrate;
                $systemCode = $this->CurrentApp->fnGenerateCode('COT', 'SalOffer');
                $this->request->data['data']['SalOffer']['system_code'] = $systemCode;

//                $this->request->data['data']['SalOffer']['total_quantity'] = 0;
//                $this->request->data['data']['SalOffer']['total'] = 0;
            }
            /////////////////
            $this->request->data['data']['SalOffer']['adm_user_id'] = $this->Session->read('Auth.User.id');
            $this->request->data['data']['SalOffer']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalOffer']['date']);
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalOffer->save($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalOffer->id, 'system_code' => $systemCode)));
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

//    Created: 23/09/2014 | Developer: reyro | Description: creates and updates
    public function fnSaveOfferAndDetail() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            /////////////ONLY FOR CREATE
            $systemCode = '';
            if ($this->request->data['data']['SalOffer']['id'] == '') {
                $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
                if ($currentExrate == '') {
                    return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'Error al obtener el cambio de moneda')))));
                }
                $this->request->data['data']['SalOffer']['ex_rate'] = $currentExrate;
                $systemCode = $this->CurrentApp->fnGenerateCode('COT', 'SalOffer');
                $this->request->data['data']['SalOffer']['system_code'] = $systemCode;

//                $this->request->data['data']['SalOffer']['total_quantity'] = 0;
//                $this->request->data['data']['SalOffer']['total'] = 0;
            }
            /////////////////
            $this->request->data['data']['SalOffer']['adm_user_id'] = $this->Session->read('Auth.User.id');
            $this->request->data['data']['SalOffer']['date'] = $this->BittionMain->fnSetFormatDate($this->request->data['data']['SalOffer']['date']);
            ///////////////////////////////////////////////////////////////////////////////////
//            $this->request->data['data2']['data']['SalOffersDetail']['subtotal'] = $this->request->data['data2']['data']['SalOffersDetail']['sale_price'] * $this->request->data['data2']['data']['SalOffersDetail']['quantity'];
            $this->request->data['data']['SalOffersDetail'][0] = $this->request->data['data2']['data']['SalOffersDetail'];
            ///////////////////////////////////////////////////////////////////////////////////
            try {
                if ($this->SalOffer->saveAssociated($this->request->data['data'])) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('id' => $this->SalOffer->id, 'system_code' => $systemCode)));
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

// Created: 23/09/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalOffer';
            //Virtual field subquery (witouth this can't sort
            $this->SalOffer->virtualFields['total'] = '(SELECT COALESCE(SUM(quantity * sale_price), 0) FROM sal_offers_details WHERE sal_offer_id = "SalOffer"."id")';
//            $this->SalOffer->virtualFields['total_quantity'] = '(SELECT COALESCE(SUM(quantity), 0) FROM sal_offers_details WHERE sal_offer_id = "SalOffer"."id")';
            $this->SalOffer->virtualFields['offer_date'] = 'TO_CHAR("SalOffer"."date", \'dd/mm/yyyy\')';
            
            $fields = array($model . '.system_code'
                , 'SalOffer.offer_date'
                , 'SalCustomer.name', $model . '.person_requesting'
//                , 'SalOffer.total_quantity'
                , 'SalOffer.total'
                , $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.system_code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'TO_CHAR(' . $model . '.date, \'dd/mm/yyyy\') LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(SalCustomer.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.person_requesting) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'CAST((SELECT COALESCE(SUM(quantity),0) FROM sal_offers_details WHERE sal_offer_id = "SalOffer"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST((SELECT COALESCE(SUM(quantity * sale_price),0) FROM sal_offers_details WHERE sal_offer_id = "SalOffer"."id") as TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR /* ,conditions AND */);
            /////////////////////////////////////
//            $this->$model->recursive = -1;
            $this->$model->unbindModel(array(
                'hasMany' => array('SalOffersDetail')
            ));
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
//SELECT "SalOffer"."system_code" AS "SalOffer__system_code", TO_CHAR("SalOffer"."date", 'dd/mm/yyyy') AS offer_date, "SalCustomer"."name" AS "SalCustomer__name", "SalOffer"."person_requesting" AS "SalOffer__person_requesting", (SELECT SUM(quantity * sale_price) FROM sal_offers_details WHERE sal_offer_id = "SalOffer"."id") as total, "SalOffer"."id" AS "SalOffer__id" FROM "public"."sal_offers" AS "SalOffer" LEFT JOIN "public"."sal_customers" AS "SalCustomer" ON ("SalOffer"."sal_customer_id" = "SalCustomer"."id") LEFT JOIN "public"."adm_users" AS "AdmUser" ON ("SalOffer"."adm_user_id" = "AdmUser"."id") WHERE ((lower("SalOffer"."system_code") LIKE '%%') OR (TO_CHAR("SalOffer"."date", 'dd/mm/yyyy') LIKE '%%') OR (lower("SalCustomer"."name") LIKE '%%') OR (lower("SalOffer"."person_requesting") LIKE '%%')) ORDER BY 5 DESC LIMIT 100
//                'order' => array('5' => 'DESC'),
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
//            $total = $this->params['paging'][$model]['pageCount'];  //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
//            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['system_code'];
                $array['aaData'][$key][1] = $value[$model]['offer_date']; //virtual field
                $array['aaData'][$key][2] = $value['SalCustomer']['name'];
                $array['aaData'][$key][3] = $value[$model]['person_requesting'];
//                $array['aaData'][$key][4] = $value[$model]['total_quantity']; //virtual field
                $array['aaData'][$key][4] = $value[$model]['total']; //virtual field
                $array['aaData'][$key][5] = $value[$model]['id']; //for edit and delete buttons
//                $counter++;
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            

            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 23/09/2014 | Developer: reyro | Description: Function Delete Customer| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->SalOffer->fnDeleteOfferandDetail($this->request->data['id']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Function Delete Customers Employee| Request: Ajax 	
    public function fnDeleteOffersDetail() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->loadModel('SalOffersDetail');
            $this->SalOffersDetail->id = $this->request->data['id'];
            try {
                if ($this->SalOffersDetail->delete()) {
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
                $this->loadModel('SalOffersDetail');
                $productDetailIds = $this->SalOffersDetail->find('list', array(
                    'fields' => array('SalOffersDetail.inv_product_id', 'SalOffersDetail.inv_product_id'),
                    'conditions' => array('SalOffersDetail.sal_offer_id' => $this->request->data['id'])
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
                $this->loadModel('SalOffersDetail');
                $total = $this->SalOffersDetail->find('first', array(
                    'fields' => array('SUM("SalOffersDetail"."quantity" * "SalOffersDetail"."sale_price") AS "total"'),
                    'conditions' => array('SalOffersDetail.sal_offer_id' => $this->request->data['offerId'])
                ));
                $response = array('total' => $total[0]['total']);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Read product total| Request: Ajax 	
    public function fnReadOfferDetailUpdate() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('SalOffersDetail');
//                $this->SalOffersDetail->recursive = -1;
                $offerDetail = $this->SalOffersDetail->find('first', array(
                    'fields' => array('SalOffersDetail.inv_product_id', 'SalOffersDetail.price', 'SalOffersDetail.sale_price', 'SalOffersDetail.quantity', 'InvProduct.description', 'InvProduct.code'),
                    'conditions' => array('SalOffersDetail.id' => $this->request->data['id'])
                ));
                $response = array($offerDetail);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 25/09/2014 | Developer: reyro | Description: Function Read Offer details for DataTable WITHOUT PAGINATION| Request: Ajax 	
    public function fnReadOffersDetail() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'SalOffersDetail';
            //Virtual field subquery (witouth this can't sort
            $this->loadModel($model);
            $this->SalOffersDetail->virtualFields['subtotal'] = '(SELECT SUM(quantity * sale_price) FROM sal_offers_details WHERE id = "SalOffersDetail"."id")';
            
            $fields = array('InvProduct.code', 'InvProduct.description', 'InvProduct.measure', $model . '.sale_price', $model . '.quantity', 'SalOffersDetail.subtotal', $model . '.id');
            $conditionsOR = array(
                'lower(InvProduct.code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(InvProduct.description) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.sale_price AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.quantity AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(InvProduct.measure) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, $model . '.sal_offer_id' => $this->request->data['offerId']);
            /////////////////////////////////////
            $this->loadModel($model);
//            $this->$model->recursive = -1;
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
            $counter = $this->request->data['iDisplayStart'] + 1;
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $counter;
                $array['aaData'][$key][1] = $value['InvProduct']['code'];
                $array['aaData'][$key][2] = $value['InvProduct']['description'];
                $array['aaData'][$key][3] = $value['InvProduct']['measure'];
                $array['aaData'][$key][4] = $value[$model]['sale_price'];
                $array['aaData'][$key][5] = $value[$model]['quantity'];
                $array['aaData'][$key][6] = $value[$model]['subtotal'];
                $array['aaData'][$key][7] = $value[$model]['id']; //for edit and delete buttons
                $counter++;
            }
//            $array['iTotalRecords'] = $total;
//            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

///////////////////////PRIVATE FUNCTIONS    
//***************************** END CONTROLLERS ********************************    
}
