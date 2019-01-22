<?php

App::uses('AppModel', 'Model');

/**
 * SalSale Model
 *
 * @property SalCustomer $SalCustomer
 * @property SalInvoice $SalInvoice
 * @property AdmUser $AdmUser
 */
class SalSale extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'sal_customer_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'adm_user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'sal_office_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'code' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'date' => array(
            'date' => array(
                'rule' => array('date'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'ex_rate' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
//        'paid' => array(
//            'numeric' => array(
//                'rule' => array('numeric'),
//            //'message' => 'Your custom message here',
//            //'allowEmpty' => false,
//            //'required' => false,
//            //'last' => false, // Stop validation after this rule
//            //'on' => 'create', // Limit validation to 'create' or 'update' operations
//            ),
//        ),
//        'debt' => array(
//            'numeric' => array(
//                'rule' => array('numeric'),
//            //'message' => 'Your custom message here',
//            //'allowEmpty' => false,
//            //'required' => false,
//            //'last' => false, // Stop validation after this rule
//            //'on' => 'create', // Limit validation to 'create' or 'update' operations
//            ),
//        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'SalCustomer' => array(
            'className' => 'SalCustomer',
            'foreignKey' => 'sal_customer_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SalInvoice' => array(
            'className' => 'SalInvoice',
            'foreignKey' => 'sal_invoice_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'SalOffice' => array(
            'className' => 'SalOffice',
            'foreignKey' => 'sal_office_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'AdmUser' => array(
            'className' => 'AdmUser',
            'foreignKey' => 'adm_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'SalSalesDetail' => array(
            'className' => 'SalSalesDetail',
            'foreignKey' => 'sal_sale_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );

    public function fnDeleteSaleAndDetail($id) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        //////////////////////////////////////////// 
        $sale = $this->find('first', array('conditions' => array('SalSale.id' => $id), 'fields' => array('SalSale.lc_state', 'SalSale.system_code'), 'recursive' => -1));
        if ($sale['SalSale']['lc_state'] != 'ELABORATED') {
            return array('status' => 'ERROR', 'title' => 'Acción denegada!', 'content' => 'No se puede eliminar una venta Aprobada o Anulada');
        }
        $details = $this->SalSalesDetail->find('list', array('fields' => array('SalSalesDetail.id', 'SalSalesDetail.id'), 'conditions' => array('SalSalesDetail.sal_sale_id' => $id)));
        foreach ($details as $detailId) { //don't use deleteAll because beforeDelete (model) won't work with triggers
//            $this->SalSalesDetail->$detailId; //doesn't work like this
            if (!$this->SalSalesDetail->delete($detailId)) {
                $dataSource->rollback();
                return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Detalle de Venta no eliminada', 'data' => $details);
            }
        }
        $this->id = $id;
        if (!$this->delete()) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Venta no eliminada');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Se eliminó permanentemente la venta: ' . $sale['SalSale']['system_code']); //message not needed here
    }

    public function fnModelApproveSale($data, $dateWithoutFormat) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ///////////////////////////////////////////
        $invoice = $this->SalInvoice->find('first', array('recursive' => -1, 'conditions' => array('SalInvoice.active' => 1, 'SalInvoice.finish_date >' => $dateWithoutFormat)));
        if (!isset($invoice['SalInvoice']['id'])) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Acción denegada!', 'content' => 'No hay una factura valida disponible');
        }
        $data['SalSale']['sal_invoice_id'] = $invoice['SalInvoice']['id'];
        $data['SalSale']['authorization_number'] = $invoice['SalInvoice']['authorization_number'];

        $invoiceNumberCount = $this->find('count', array('conditions' => array('SalSale.sal_invoice_id' => $invoice['SalInvoice']['id'], 'SalSale.sal_office_id'=>$data['SalSale']['sal_office_id'],'SalSale.lc_state'=>array('APPROVED', 'CANCELED'))));
        $data['SalSale']['invoice_number'] = $invoiceNumberCount + 1;

        $totals = $this->fnGetTotalsAndDiscount($data['SalSale']['id'], $data['SalSale']['discount']);
        $data['SalSale']['paid'] = $totals['totalFinal'];
        $invoiceControlCode = $this->fnGenerateInvoiceControlCode($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']);
//        debug(array($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']));
        if ($invoiceControlCode == '') {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Fallo al generar el codigo de control de la factura');
        }
        $data['SalSale']['control_code'] = $invoiceControlCode;
        ////////////////////////////////////////////
        if (!$this->save($data)) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'No se pudo APROBAR la venta');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Venta aprobada!', 'data' => array('authorizationNumber' => $invoice['SalInvoice']['authorization_number'], 'invoiceNumber' => $data['SalSale']['invoice_number'], 'invoiceControlCode' => $invoiceControlCode)); //message not needed here
    }

//    Created: 05/06/2015 | Developer: reyro | Description: Generate Invoice
    public function fnModelAdminGenerateInvoice($data, $dateWithoutFormat) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ///////////////////////////////////////////
        $invoice = $this->SalInvoice->find('first', array('recursive' => -1, 'conditions' => array('SalInvoice.active' => 1, 'SalInvoice.finish_date >' => $dateWithoutFormat)));
        if (!isset($invoice['SalInvoice']['id'])) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Acción denegada!', 'content' => 'No hay una factura valida disponible');
        }
        $data['SalSale']['sal_invoice_id'] = $invoice['SalInvoice']['id'];
        $data['SalSale']['authorization_number'] = $invoice['SalInvoice']['authorization_number'];

        $invoiceNumberCount = $this->find('count', array('conditions' => array('SalSale.control_code !='=> null,'SalSale.sal_invoice_id' => $invoice['SalInvoice']['id'], 'SalSale.sal_office_id'=>$data['SalSale']['sal_office_id'],'SalSale.lc_state'=>array('APPROVED', 'CANCELED'))));
        $data['SalSale']['invoice_number'] = $invoiceNumberCount + 1;

        $totals = $this->fnGetTotalsAndDiscount($data['SalSale']['id'], $data['SalSale']['discount']);
        $data['SalSale']['paid'] = $totals['totalFinal'];
        $invoiceControlCode = $this->fnGenerateInvoiceControlCode($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']);
//        debug(array($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']));
        if ($invoiceControlCode == '') {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Fallo al generar el codigo de control de la factura');
        }
        $data['SalSale']['control_code'] = $invoiceControlCode;
        ////////////////////////////////////////////
        if (!$this->save($data)) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'No se pudo Facturar');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Venta Facturada!', 'data' => array('authorizationNumber' => $invoice['SalInvoice']['authorization_number'], 'invoiceNumber' => $data['SalSale']['invoice_number'], 'invoiceControlCode' => $invoiceControlCode)); //message not needed here
    }

//    Created: 04/06/2015 | Developer: reyro | Description: Approve a sale without invoice
    public function fnModelAdminApproveSale($data, $dateWithoutFormat) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ///////////////////////////////////////////
//        $invoice = $this->SalInvoice->find('first', array('recursive' => -1, 'conditions' => array('SalInvoice.active' => 1, 'SalInvoice.finish_date >' => $dateWithoutFormat)));
//        if (!isset($invoice['SalInvoice']['id'])) {
//            $dataSource->rollback();
//            return array('status' => 'ERROR', 'title' => 'Acción denegada!', 'content' => 'No hay una factura valida disponible');
//        }
//        $data['SalSale']['sal_invoice_id'] = $invoice['SalInvoice']['id'];
//        $data['SalSale']['authorization_number'] = $invoice['SalInvoice']['authorization_number'];

//        $invoiceNumberCount = $this->find('count', array('conditions' => array('SalSale.sal_invoice_id' => $invoice['SalInvoice']['id'], 'SalSale.sal_office_id'=>$data['SalSale']['sal_office_id'],'SalSale.lc_state'=>array('APPROVED', 'CANCELED'))));
//        $data['SalSale']['invoice_number'] = $invoiceNumberCount + 1;


        $totals = $this->fnGetTotalsAndDiscount($data['SalSale']['id'], $data['SalSale']['discount']);
        $data['SalSale']['paid'] = $totals['totalFinal'];
//        $invoiceControlCode = $this->fnGenerateInvoiceControlCode($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']);
//        debug(array($invoice['SalInvoice']['authorization_number'], $data['SalSale']['invoice_number'], $data['SalSale']['customer_nit'], $dateWithoutFormat, $totals['totalFinal'], $invoice['SalInvoice']['control_key']));
//        if ($invoiceControlCode == '') {
//            $dataSource->rollback();
//            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Fallo al generar el codigo de control de la factura');
//        }
//        $data['SalSale']['control_code'] = $invoiceControlCode;
        ////////////////////////////////////////////
        if (!$this->save($data)) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'No se pudo APROBAR la venta');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Venta aprobada!', 'data' => array()); //message not needed here
    }

    public function fnGenerateInvoiceControlCode($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey) {
//        $totals = $this->fnGetTotalsAndDiscount($saleId, $discount);
//        $totalFinal = $totals['totalFinal'];
        if($nit == ''){
            $nit = 0;
        }
        $arrayDate = explode('/',$invoiceDate); //d/m/Y
        $invoiceDate = $arrayDate[2].$arrayDate[1].$arrayDate[0]; //Ymd = inverted without spaces
        
        $finalTotalWithoutDecimals = explode('.', $finalTotal); //take off the decimals
        $finalTotal = $finalTotalWithoutDecimals[0];
        
//        debug(array($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey));
        
        $dirVendors = App::path('Vendor'); //[0] is /app/Vendor and 1 is /vendors
        require_once $dirVendors[0] . 'CodigoControl.php';
        $obj = new CodigoControl($authorizationNumber, $invoiceNumber, $nit, $invoiceDate, $finalTotal, $controlKey);
        $controlCode = $obj->generar();
        return $controlCode;
    }

    public function fnGetTotalsAndDiscount($saleId, $discount) {
        $total = $this->SalSalesDetail->find('first', array(
            'fields' => array('SUM("SalSalesDetail"."quantity" * "SalSalesDetail"."sale_price") AS "total"'),
            'conditions' => array('SalSalesDetail.sal_sale_id' => $saleId)
        ));
//        $discount = $this->find('first', array('fields' => array('SalSale.discount'), 'recursive' => -1, 'conditions' => array('SalSale.id' => $saleId)));
        $discount = ($total[0]['total'] * $discount) / 100;
        $totalFinal = $total[0]['total'] - number_format($discount, 2, '.', '');
        return array('total' => $total, 'discount' => $discount, 'totalFinal' => $totalFinal);
    }

}
