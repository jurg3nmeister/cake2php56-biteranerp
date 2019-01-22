<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php

App::uses('AppModel', 'Model');

/**
 * AdmController Model
 *
 * @property AdmModule $AdmModule
 * @property AdmState $AdmState
 * @property AdmAction $AdmAction
 */
class AdmController extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'adm_module_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'content' => 'Your custom content here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'content' => 'Your custom content here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            //'content' => 'Your custom content here',
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
        'AdmModule' => array(
            'className' => 'AdmModule',
            'foreignKey' => 'adm_module_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'AdmState' => array(
            'className' => 'AdmState',
            'foreignKey' => 'adm_controller_id',
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
        'AdmAction' => array(
            'className' => 'AdmAction',
            'foreignKey' => 'adm_controller_id',
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
        'AdmTransaction' => array(
            'className' => 'AdmTransaction',
            'foreignKey' => 'adm_controller_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    public function fnCreateControlWithLifeCycles($data) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ////////////////////////////////////////////////
        $initials = $data['AdmModule']['initials'];
        unset($data['AdmModule']['initials']);
        $findModuleId = $this->AdmModule->find('first', array('fields' => 'AdmModule.id', 'recursive' => -1, 'conditions' => array('AdmModule.initials' => $initials)));
        $moduleId = $findModuleId['AdmModule']['id'];
        if ($moduleId == '') {
            $dataSource->rollback();
            return array('status'=>'ERROR', 'title'=>'Error!', 'content'=>'Módulo Id no encontrado');
        }
        $data['AdmController']['adm_module_id'] = $moduleId;

        //Save controller
        if (!$this->save($data)) {
            $dataSource->rollback();
            return array('status'=>'ERROR', 'title'=>'Error!', 'content'=>'Controlador no guardado');
        }
        $idController = $this->getInsertID();

        $admTransactions = array(
            array("adm_controller_id" => $idController, "name" => "CREATE", "description" => "Crear un fila"),
            array("adm_controller_id" => $idController, "name" => "UPDATE", "description" => "Editar un o varios campos de una fila"),
            array("adm_controller_id" => $idController, "name" => "DELETE", "description" => "Eliminar un fila")
        );

        //Save transaction
        if (!$this->AdmTransaction->saveMany($admTransactions)) {
            $dataSource->rollback();
            return array('status'=>'ERROR', 'title'=>'Error!', 'content'=>'Transacciones no guardadas');
        }

        $admStates = array(
            array("adm_controller_id" => $idController, "name" => "INITIAL", "description" => "Estado Inicial (No queda registrado en la BD)"),
            array("adm_controller_id" => $idController, "name" => "ELABORATED", "description" => "Estado Elaborado"),
            array("adm_controller_id" => $idController, "name" => "FINAL", "description" => "Estado Final (No queda registrado en la BD)")
        );
        //Save states
        if (!$this->AdmState->saveMany($admStates)) {
            $dataSource->rollback();
            return array('status'=>'ERROR', 'title'=>'Error!', 'content'=>'Estados no guardados');
        }

        $savedStates = $this->AdmState->find('list', array("conditions" => array("AdmState.adm_controller_id" => $idController), "fields" => array("AdmState.id", "AdmState.id"), "order"=>array("AdmState.id"=>"ASC")));
        $counter = 0;
        foreach ($savedStates as $value) {
            $formatSavedStates[$counter] = $value;
            $counter++;
        }
        
        $savedTransactions = $this->AdmTransaction->find('list', array("conditions" => array("AdmTransaction.adm_controller_id" => $idController), "fields" => array("AdmTransaction.id", "AdmTransaction.id"), "order"=>array("AdmTransaction.id"=>"ASC")));
        $counter = 0;
        foreach ($savedTransactions as $value) {
            $formatSavedTransactions[$counter] = $value;
            $counter++;
        }

        $admTransitions = array(
            array("adm_state_id" => $formatSavedStates[0], "adm_transaction_id" => $formatSavedTransactions[0], "adm_final_state_id" => $formatSavedStates[1]),
            array("adm_state_id" => $formatSavedStates[1], "adm_transaction_id" => $formatSavedTransactions[1], "adm_final_state_id" => $formatSavedStates[1]),
            array("adm_state_id" => $formatSavedStates[1], "adm_transaction_id" => $formatSavedTransactions[2], "adm_final_state_id" => $formatSavedStates[2])
        );
        //Save transitions
        if (!$this->AdmState->AdmTransition->saveMany($admTransitions)) {
            $dataSource->rollback();
            return array('status'=>'ERROR', 'title'=>'Error!', 'content'=>'Transiciones no guardadas');
        }

        ///////////////////////////////////////////////
        $dataSource->commit();
        return array('status'=>'SUCCESS', 'title'=>'Exito!', 'content'=>'Datos guardados');//content not needed here
    }

//END CLASS
}
