<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php

App::uses('AppModel', 'Model');

/**
 * AdmTransition Model
 *
 * @property AdmState $AdmState
 * @property AdmAction $AdmAction
 */
class AdmTransition extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'adm_state_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'adm_transaction_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'adm_final_state_id' => array(
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
        'AdmState' => array(
            'className' => 'AdmState',
            'foreignKey' => 'adm_state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'AdmTransaction' => array(
            'className' => 'AdmTransaction',
            'foreignKey' => 'adm_transaction_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'AdmFinalState' => array(
            'className' => 'AdmState',
            'foreignKey' => 'adm_final_state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
