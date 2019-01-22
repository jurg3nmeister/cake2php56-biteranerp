<?php

/* (c)Bittion Admin Module | Created: 22/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmMenusController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 22/08/2014 | Developer: reyro | Description: CRUD Menus, all in one
    public function index() {
        $this->set('menu',$this->_fnGetActions());
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 23/08/2014 | Developer: reyro | Description: Function create or update | Request: Ajax
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            if ($this->request->data['data']['AdmMenu']['id'] == '') {
                $response = $this->_fnCreate($this->request->data['data']);
            } else {
                $response = $this->_fnUpdate($this->request->data['data']);
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: read multilevel treeview menus | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            $level1 = $this->_fnGetMenus(null);
            $level2 = $this->_fnGetMenus($level1['menusIds']);
            $level3 = $this->_fnGetMenus($level2['menusIds']);
            $multiLevelMenu = $this->_fnOrderMultiLevelMenu($level1['menus'], $level2['menus'], $level3['menus']);
            return new CakeResponse(array('body' => json_encode($multiLevelMenu)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 23/08/2014 | Developer: reyro | Description: read data for update | Request: Ajax 	
    public function fnReadUpdate() {
        if ($this->RequestHandler->isAjax()) {
            $response['update'] = $this->AdmMenu->read(null, $this->request->data['id']);
            $response['actions'] = $this->_fnGetActions($this->request->data['id']);
            $response['menus'] = $this->_fnReadParents($this->request->data['level']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: read all actions with controllers| Request: Ajax 	    
    public function fnReadActions() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->_fnGetActions();
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->AdmMenu->id = $this->request->data['id'];
            try {
                if ($this->AdmMenu->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content'=>'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

////////////////////////PRIVATE FUNCTIONS
    //    Created: 22/08/2014 | Developer: reyro | Description: Function Create | Request: Ajax
    private function _fnCreate($data) {
        $response = $this->BittionMain->fnGetMethodResponse('ERROR');
        $data = Set::filter($data); //remove empty values, to save null instead of ''
//        debug($data);
        if ($data['AdmMenu']['parent'] === '0') {
            $data['AdmMenu']['parent'] = null;
        }
        $this->AdmMenu->create();
        try {
            if ($this->AdmMenu->save($data)) {
                $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
            }
        } catch (Exception $exc) {
            $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
        }
        return $response;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: Function Update | Request: Ajax 	
    private function _fnUpdate($data) {
        $response = $this->BittionMain->fnGetMethodResponse('ERROR');
        if($data['AdmMenu']['icon'] == ''){
           $data['AdmMenu']['icon'] = null; 
        }
        if ($data['AdmMenu']['id'] != '') {
            try {
                if ($this->AdmMenu->save($data)) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS');
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
        }
        return $response;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: order 3 levels arrays into 1 multi level array
    private function _fnOrderMultiLevelMenu($menus1, $menus2, $menus3) {
        //3 levels
        $multiLevelMenu = array();
        foreach ($menus1 as $menu1) { //LEVEL 1
            $keyMenu1 = $menu1['AdmMenu']['order_menu'] . '-' . $menu1['AdmMenu']['id'];
            $multiLevelMenu[$keyMenu1]['menu'] = $menu1['AdmMenu']; // declare menu1
            $multiLevelMenu[$keyMenu1]['children'] = array(); //declare children 1

            foreach ($menus2 as $menu2) { //LEVEL 2
                if ($menu1['AdmMenu']['id'] == $menu2['AdmMenu']['parent']) {
                    $keyMenu2 = $menu2['AdmMenu']['order_menu'] . '-' . $menu2['AdmMenu']['id'];
                    $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['menu'] = $menu2['AdmMenu']; //declare menu2
                    $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['children'] = array(); //declare children 2

                    foreach ($menus3 as $menu3) { //LEVEL 3
                        if ($menu2['AdmMenu']['id'] == $menu3['AdmMenu']['parent']) {
                            $keyMenu3 = $menu3['AdmMenu']['order_menu'] . '-' . $menu3['AdmMenu']['id'];
                            $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['children'][$keyMenu3]['menu'] = $menu3['AdmMenu']; //declare menu 3
                        }
                    }
                }
            }
        }
        return $multiLevelMenu;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: generates multiarray 3 formated 3 levels menu
    private function _fnFormatLevel3Parents($menus1, $menus2) {
        //2 levels
        $counter = 0;
        $array = array();
        foreach ($menus1 as $menu1) { //LEVEL 1
            $array[$counter] = $menu1;
            $counter++;
            foreach ($menus2 as $menu2) { //LEVEL 2
                if ($menu1['AdmMenu']['id'] == $menu2['AdmMenu']['parent']) {
                    $menu2['AdmMenu']['name'] = $menu1['AdmMenu']['name'] . ' > ' . $menu2['AdmMenu']['name'];
                    $array[$counter] = $menu2;
                    $counter++;
                }
            }
        }
        return $array;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: get menus arrays for every level according to parents   
    private function _fnGetMenus($parents) {
        $menus = $this->AdmMenu->find('all', array(
            'recursive' => -1,
            'fields' => array('AdmMenu.id', 'AdmMenu.name', 'AdmMenu.order_menu', 'AdmMenu.parent', 'AdmMenu.icon'),
            'conditions' => array('AdmMenu.parent' => $parents),
//            'order' => array('AdmMenu.order_menu' => 'ASC')
            "order" => array("AdmMenu.order_menu", "AdmMenu.name"),
        ));
        $menusIds = array();
        foreach ($menus as $key => $value) {
            $menusIds[$key] = $value['AdmMenu']['id'];
        }
        return array('menus' => $menus, 'menusIds' => $menusIds);
    }

//    Created: 22/08/2014 | Developer: reyro | Description: list all actions
    private function _fnGetActions($currentMenuId = 0) {
        //****for only one action per menu - IMPORTANT: active or desactive DB constraint UNIQUE
        $menusActionsIds = $this->AdmMenu->find('list', array('fields' => array('AdmMenu.id', 'AdmMenu.adm_action_id'), 'conditions'=>array('AdmMenu.adm_action_id !='=>null)));
        if (isset($menusActionsIds[$currentMenuId])) {
            unset($menusActionsIds[$currentMenuId]);    
        }
        //****************************************
        
        $this->AdmMenu->AdmAction->unbindModel(array(
            'hasMany' => array('AdmMenu')
        ));
        $actionsCake = $this->AdmMenu->AdmAction->find('all', array(
            'recursive' => 1,
            'fields' => array('AdmAction.id', 'AdmAction.name', 'AdmController.name'),
            'order' => array('AdmController.name' => 'ASC'),
            'conditions'=>array('NOT'=>array('AdmAction.id'=>$menusActionsIds)) //****for only one action per menu
        ));
        $actions = array();
        foreach ($actionsCake as $value) {
            $actions[$value["AdmAction"]["id"]] = Inflector::camelize($value["AdmController"]["name"]) . " " . $value["AdmAction"]["name"];
        }
        return $actions;
    }

//    Created: 22/08/2014 | Developer: reyro | Description: list parents by level    
    private function _fnReadParents($level) {
        $parents = array();
        switch ($level) {
            case 1: // update level 1 = no parents
                break;
            case 2: // update level 2 = just level 1 as parents
                $level1 = $this->_fnGetMenus(null);
                $parents = $level1;
                break;
            case 3: // update level 3 = level 1 and level 2 as parents
                $level1 = $this->_fnGetMenus(null);
                $level2 = $this->_fnGetMenus($level1['menusIds']);
//                $parents = array_merge($level1, $level2);
                $parents['menus'] = $this->_fnFormatLevel3Parents($level1['menus'], $level2['menus']);
                break;
        }
        return $parents;
    }

    //***************************** END CONTROLLERS ********************************
}
