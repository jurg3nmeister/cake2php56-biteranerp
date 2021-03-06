<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php

App::uses('AppModel', 'Model');

/**
 * AdmRolesTransaction Model
 *
 * @property AdmRole $AdmRole
 * @property AdmTransaction $AdmTransaction
 */
class AdmRolesTransaction extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'adm_role_id' => array(
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
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'AdmRole' => array(
            'className' => 'AdmRole',
            'foreignKey' => 'adm_role_id',
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
        )
    );

//    Created: 21/08/2014 | Developer: reyro | Description: Insert and delete roles transactions | Obs: Transaction
    public function fnSaveAndDelete($roleId, $insert, $delete) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ////////////////////////Deletes
        //deleteAll() won't be catched by beforeDelete() in the Model
        if (count($delete) > 0) {
            //MUST do this way extra step, in order for beforeDelete to catch row_id and for the trigger delete to work
            $deleteIds = $this->find('list', array(
                'conditions' => array('AdmRolesTransaction.adm_role_id' => $roleId, 'AdmRolesTransaction.adm_transaction_id' => $delete),
                'fields' => array('AdmRolesTransaction.id', 'AdmRolesTransaction.id')
            ));
            //MUST use foreach and delete one by one, because deleteAll() is not catched by beforeDelete
            foreach ($deleteIds as $deleteId) {
                try {
                    $this->id = $deleteId;
                    $this->delete();
                } catch (Exception $e) {
//				debug($e);
                    $dataSource->rollback();
                    return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Error al eliminar');
                }
            }
        }
        ////////////////////////inserts
        if (count($insert) > 0) {
            //Format data to match SaveMany array format
            $saveManyArray = array();
            $counter = 0;
            foreach ($insert as $transactionId) {
                $saveManyArray[$counter]['adm_role_id'] = $roleId;
                $saveManyArray[$counter]['adm_transaction_id'] = $transactionId;
                $counter++;
            }
            if (!$this->saveMany($saveManyArray)) {
                $dataSource->rollback();
                return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Error al insertar');
            }
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Datos guardados');
    }

//END CLASS
}
