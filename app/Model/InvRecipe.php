<?php

App::uses('AppModel', 'Model');

/**
 * InvRecipe Model
 *
 * @property InvPrice $InvPrice
 *
 */
class InvRecipe extends AppModel {

    /**
     * Display field
     *
     * @var string
     */

//    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'inv_product_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'inv_product_ingredient_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'quantity' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
    public $belongsTo = array(
        'InvProduct' => array(
            'className' => 'InvProduct',
            'foreignKey' => 'inv_product_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'InvProductIngredient' => array(
            'className' => 'InvProduct',
            'foreignKey' => 'inv_product_ingredient_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function fnModelUpdate($data) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ////////////////////////////////////////////
        $lastPrice = $this->InvPrice->find('first', array('conditions' => array('InvPrice.inv_product_id' => $data['InvRecipe']['id']), 'order' => array('InvPrice.id' => 'DESC'), 'fields' => array('InvPrice.price')));
        if(!isset($data['InvRecipe']['picture'])/* || $data['InvRecipe']['picture'] == ''*/) {
            if ($lastPrice['InvPrice']['price'] != $data['InvPrice'][0]['price']) {
                $price = array('price' => $data['InvPrice'][0]['price'], 'ex_rate' => $data['InvPrice'][0]['ex_rate'], 'inv_product_id' => $data['InvRecipe']['id']);
                if (!$this->InvPrice->save($price)) {
                    $dataSource->rollback();
                    return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Precio no guardado');
                }
            }
        }
        if (!$this->save($data)) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Producto no guardado');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Datos guardados', 'data' => array('id'=>$this->id)); //message not needed here
    }
    
    public function fnDelete($id) {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ////////////////////////////////////////////
        $prices = $this->InvPrice->find('list', array('fields' => array('InvPrice.id', 'InvPrice.id'), 'conditions' => array('InvPrice.inv_product_id' => $id)));
        foreach ($prices as $value) {
            $this->InvPrice->id = $value;
            if (!$this->InvPrice->delete()) {
                $dataSource->rollback();
                return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Precio no eliminado');
            }
        }

        $this->id = $id;
        if (!$this->delete()) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Producto no eliminado');
        }
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Producto eliminado'); //message not needed here
    }

//END CLASS
}
