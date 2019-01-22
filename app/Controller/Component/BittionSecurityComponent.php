<?php

/*
 * Bittion
 * developer:reyro
 * created: 31/08/2014
 */

class BittionSecurityComponent extends Component {

//  Created: 31/08/2014 | Developer: reyro | Description: Checks if the user's role has permission for this action (view or function for ajax)  
    public function fnAllowActionPermission($controllerName, $actionName, $roleId) {
        $controllerName = $this->fnDecamelizeToUnderscore($controllerName);
        $alwaysAuthorized = $this->_fnAuthorizeAlways($controllerName, $actionName);
        if (substr($actionName, 0, 2) == 'fn' OR $alwaysAuthorized == true) {//Always allow if public function without view (ajax requests)
            return true;
        }
        $this->AdmRolesAction = ClassRegistry::init('AdmRolesAction');
        $this->AdmRolesAction->unbindModel(array('belongsTo' => array('AdmRole')));
        $this->AdmRolesAction->bindModel(array('belongsTo' => array(
                'AdmController' => array('foreignKey' => false, 'conditions' => array('AdmAction.adm_controller_id = AdmController.id'))
            )
        ));
        $response = $this->AdmRolesAction->find('count', array(
            'conditions' => array(
                'AdmRolesAction.adm_role_id' => $roleId,
                'AdmAction.name' => $actionName,
                'AdmController.name' => $controllerName
        )));
        if ($response == 0) {
            return false;
        }
        return true;
    }

//  Created: 31/08/2014 | Developer: reyro | Description: Check if a user is active or not logic_deleted
    public function fnCheckActiveUser($userId) {
        $this->AdmUser = ClassRegistry::init('AdmUser');
        $response = $this->AdmUser->find('count', array(
            'conditions' => array('AdmUser.id' => $userId, 'AdmUser.active' => true, 'AdmUser.lc_state !=' => 'LOGIC_DELETED')
        ));
        if ($response == 0) {
            return false;
        }
        return true;
    }

//  Created: 31/08/2014 | Developer: reyro | Description: Converts CamelCase to camel_case
    public function fnDecamelizeToUnderscore($string) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

//  Created: 31/08/2014 | Developer: reyro | Description: Check if the current controller.action is in the array of always authorized
    private function _fnAuthorizeAlways($controllerName, $actionName) {
        $checkValue = $controllerName . '.' . $actionName;
        $authorized = array('adm_users.login' => 'adm_users.login', 'adm_users.logout' => 'adm_users.logout');
        $authorized['adm_users.home'] = 'adm_users.home';
        if (isset($authorized[$checkValue])) {
            return true;
        }
        return false;
    }

}
