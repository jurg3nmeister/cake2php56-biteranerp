<?php

App::uses('AppModel', 'Model');

/**
 * SalOffer Model
 *
 * @property SalCustomer $SalCustomer
 * @property AdmUser $AdmUser
 */
class SalOffer extends AppModel {

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
        'system_code' => array(
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
        'total_quantity' => array(
            'numeric' => array(
                'rule' => array('numeric'),
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
        'total' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
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
        'AdmUser' => array(
            'className' => 'AdmUser',
            'foreignKey' => 'adm_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'SalOffersDetail' => array(
            'className' => 'SalOffersDetail',
            'foreignKey' => 'sal_offer_id',
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

    public function fnDeleteOfferandDetail($id) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        //////////////////////////////////////////// 
        $details = $this->SalOffersDetail->find('list', array('fields' => array('SalOffersDetail.id', 'SalOffersDetail.id'), 'conditions' => array('SalOffersDetail.sal_offer_id' => $id)));
        foreach ($details as $detailId) { //don't use deleteAll because beforeDelete (model) won't work with triggers
//            $this->SalOffersDetail->$detailId; //doesn't work like this
            if (!$this->SalOffersDetail->delete($detailId)) {
                $dataSource->rollback();
                return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Detalle de Oferta no eliminada', 'data'=>$details);
            }
        }
        $this->id = $id;
        if (!$this->delete()) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Oferta no eliminada');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Eliminado'); //message not needed here
    }

}
