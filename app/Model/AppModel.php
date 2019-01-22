<?php /* (c)Bittion Admin Module | Created: 30/08/2014 | Developer:reyro */ ?>
<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public function beforeSave($options = array()) { //DO NOT USE updateALL(), triggers won't work
        App::import('Model', 'CakeSession');
        $session = new CakeSession();
        $userId = $session->read('Auth.User.id');

        if ($userId == '') {
            return false;
        }
//        The trigger will figure it out wich one to put in which
//        $this->data[$this->name]['creator'] = $userId;
//        $this->data[$this->name]['modifier'] = $userId;
        
        
        if(isset($this->data[$this->name]['id']) && $this->data[$this->name]['id'] != ''){//for triggers update
            $this->data[$this->name]['modifier'] = $userId;
        }else{
            $this->data[$this->name]['creator'] = $userId;
        }
        
        return true;
    }

    public function beforeDelete($cascade = false) { //DO NOT USE deleteALL(), triggers won't work

        App::import('Model', 'CakeSession');
        $session = new CakeSession();
        $userId = $session->read('Auth.User.id');

        if ($userId == '') {
            return false;
        }
        try {
            $this->save(array('id' => $this->id, 'modifier' => $userId));
            return true;
        } catch (Exception $exc) {
//			echo $exc->getTraceAsString();
            return false;
        }
    }

}
