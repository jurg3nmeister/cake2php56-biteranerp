<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php

App::uses('AppModel', 'Model');

/**
 * AdmUser Model
 *
 * @property AdmProfile $AdmProfile
 * @property AdmLogin $AdmLogin
 * @property AdmUserRestriction $AdmUserRestriction
 */
class AdmUser extends AppModel
{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'password' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
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
        'active' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    public $hasOne = array(
        'AdmProfile' => array(
            'className' => 'AdmProfile',
            'foreignKey' => 'adm_user_id',
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
    public $belongsTo = array(
        'AdmRole' => array(
            'className' => 'AdmRole',
            'foreignKey' => 'adm_role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    public $hasMany = array(
        'AdmLogin' => array(
            'className' => 'AdmLogin',
            'foreignKey' => 'adm_user_id',
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

    public function fnSaveTransac($data, $unencryptedPassword)
    {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
        ///////////////////////////////////////////

        //Check if User is saved before save profile data
        if(isset($data['AdmUser']['username'])){  //always create
            if($data['AdmUser']['username'] == ''){
                $dataSource->rollback();
                return array('status' => 'ERROR', 'title' => 'No se guardó!', 'content' => 'Falta el nombre de usuario');
            }
        }

        if($data['AdmUser']['adm_role_id'] == ''){
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'No se guardó!', 'content' => 'Falta el rol del usuario');
        }

        //Save first AdmUser
        if (!$this->save($data['AdmUser'])) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Usuario no guardado');
        }
        $userId = $this->id;

        //Check if userId is not empty
        if($userId == ''){
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'No se pudo recuperar el identificador de usuario');
        }
        $data['AdmProfile']['adm_user_id'] = $userId;


        //Check if AdmProfile exists for create or update
        $profileId = $this->AdmProfile->find('first', array('fields' => array('AdmProfile.id'), 'conditions' => array('AdmProfile.adm_user_id' => $userId)));
        if(count($profileId) > 0){ //update
            $data['AdmProfile']['id'] = $profileId['AdmProfile']['id'];
            $unencryptedPassword = '';
        }

        //Save AdmProfile
        if (!$this->AdmProfile->save($data['AdmProfile'])) {
            $dataSource->rollback();
            return array('status' => 'ERROR', 'title' => 'Error!', 'content' => 'Perfil de usuario no guardado');
        }


//        $profileId = $this->AdmProfile->find('first', array('fields' => array('AdmProfile.id'), 'conditions' => array('AdmProfile.adm_user_id' => $data['AdmUser']['id'])));
        ///////////////////////////////////////////
        $dataSource->commit();
        return array('status' => 'SUCCESS', 'title' => 'Exito!', 'content' => 'Guardado con exito', 'data'=>array('userId'=>$userId, 'email'=>$data['AdmProfile']['email'], 'password'=>$unencryptedPassword)); //message not needed here
    }
///////////
}
