<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php

App::uses('AppModel', 'Model');

/**
 * AdmRole Model
 *
 * @property AdmModule $AdmModule
 * @property AdmNodesRolesUser $AdmNodesRolesUser
 * @property AdmRolesMenu $AdmRolesMenu
 * @property AdmRolesTransaction $AdmRolesTransaction
 */
class AdmRole extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
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

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'AdmUser' => array(
            'className' => 'AdmUser',
            'foreignKey' => 'adm_role_id',
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
        'AdmRolesMenu' => array(
            'className' => 'AdmRolesMenu',
            'foreignKey' => 'adm_role_id',
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
        'AdmRolesAction' => array(
            'className' => 'AdmRolesAction',
            'foreignKey' => 'adm_role_id',
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
        'AdmRolesTransaction' => array(
            'className' => 'AdmRolesTransaction',
            'foreignKey' => 'adm_role_id',
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

}
