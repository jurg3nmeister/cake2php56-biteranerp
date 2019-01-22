<?php

/* (c)Bittion Admin Module | Created: 24/08/2014 | Developer:reyro */

App::uses('AppController', 'Controller');

class AdmRolesMenusController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 24/08/2014 | Developer: reyro | Description: View index, list and save roles menus | Request:Ajax 
    public function index() {
        $roles = $this->AdmRolesMenu->AdmRole->find('list');
        $this->set(compact('roles'));
    }

////////////////////////PUBLIC FUNCTIONS
//    Created: 24/08/2014 | Developer: reyro | Description: function list all menu roles | Request:Ajax
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            $menusUsedIds = $this->AdmRolesMenu->find('list', array('fields' => array('AdmRolesMenu.adm_menu_id', 'AdmRolesMenu.adm_menu_id'), 'conditions' => array('AdmRolesMenu.adm_role_id' => $this->request->data['roleId'])));
            $level1 = $this->_fnGetMenus(null);
            $level2 = $this->_fnGetMenus($level1['menusIds']);
            $level3 = $this->_fnGetMenus($level2['menusIds']);
            $multiLevelMenu = $this->_fnOrderMultiLevelMenu($level1['menus'], $level2['menus'], $level3['menus'], $menusUsedIds);
            $empty = count($menusUsedIds);
            return new CakeResponse(array('body' => json_encode(array('menu'=>$multiLevelMenu, 'empty'=>$empty))));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 24/08/2014 | Developer: reyro | Description: insert and delete roles menus | Request:Ajax 
    public function fnCreate() {
        if ($this->RequestHandler->isAjax()) {
            $oldValues = $this->AdmRolesMenu->find('list', array('fields' => array('AdmRolesMenu.adm_menu_id', 'AdmRolesMenu.adm_menu_id'),
                'conditions' => array('AdmRolesMenu.adm_role_id' => $this->request->data['data']['AdmRolesMenu']['adm_role_id'])));
            $newValues = array();
            if (isset($this->request->data['menusIds'])) {
                $newValues = $this->request->data['menusIds'];
            }
            $insert = array_diff($newValues, $oldValues);
            $delete = array_diff($oldValues, $newValues);
            $response = $this->AdmRolesMenu->fnSaveAndDelete($this->request->data['data']['AdmRolesMenu']['adm_role_id'], $insert, $delete);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }    

////////////////////////PRIVATE FUNCTIONS 
//    Created: 24/08/2014 | Developer: reyro | Description: get menus arrays for every level according to parents   
    private function _fnGetMenus($parents) {
        $menus = $this->AdmRolesMenu->AdmMenu->find('all', array(
            'recursive' => -1,
            'fields' => array('AdmMenu.id', 'AdmMenu.name', 'AdmMenu.order_menu', 'AdmMenu.parent', 'AdmMenu.icon'),
            'conditions' => array('AdmMenu.parent' => $parents),
            "order" => array("AdmMenu.order_menu", "AdmMenu.name"),
        ));
        $menusIds = array();
        foreach ($menus as $key => $value) {
            $menusIds[$key] = $value['AdmMenu']['id'];
        }
        return array('menus' => $menus, 'menusIds' => $menusIds);
    }

//    Created: 24/08/2014 | Developer: reyro | Description: order 3 levels arrays into 1 multi level array
    private function _fnOrderMultiLevelMenu($menus1, $menus2, $menus3, $menusUsedIds) {
        //3 levels
        $multiLevelMenu = array();
        foreach ($menus1 as $menu1) { //LEVEL 1
            $keyMenu1 = $menu1['AdmMenu']['order_menu'] . '-' . $menu1['AdmMenu']['id'];
            $multiLevelMenu[$keyMenu1]['menu'] = $menu1['AdmMenu']; // declare menu1
            $multiLevelMenu[$keyMenu1]['menu']['checked'] = $this->_fnCheckIfExists($menu1['AdmMenu']['id'], $menusUsedIds);
            $multiLevelMenu[$keyMenu1]['children'] = array(); //declare children 1

            foreach ($menus2 as $menu2) { //LEVEL 2
                if ($menu1['AdmMenu']['id'] == $menu2['AdmMenu']['parent']) {
                    $keyMenu2 = $menu2['AdmMenu']['order_menu'] . '-' . $menu2['AdmMenu']['id'];
                    $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['menu'] = $menu2['AdmMenu']; //declare menu2
                    $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['menu']['checked'] = $this->_fnCheckIfExists($menu2['AdmMenu']['id'], $menusUsedIds);
                    $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['children'] = array(); //declare children 2

                    foreach ($menus3 as $menu3) { //LEVEL 3
                        if ($menu2['AdmMenu']['id'] == $menu3['AdmMenu']['parent']) {
                            $keyMenu3 = $menu3['AdmMenu']['order_menu'] . '-' . $menu3['AdmMenu']['id'];
                            $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['children'][$keyMenu3]['menu'] = $menu3['AdmMenu']; //declare menu 3
                            $multiLevelMenu[$keyMenu1]['children'][$keyMenu2]['children'][$keyMenu3]['menu']['checked'] = $this->_fnCheckIfExists($menu3['AdmMenu']['id'], $menusUsedIds);
                        }
                    }
                }
            }
        }
        return $multiLevelMenu;
    }

//    Created: 24/08/2014 | Developer: reyro | Description: check if exists a role menu
    private function _fnCheckIfExists($menuId, $menusUsedIds) {
        if(in_array($menuId, $menusUsedIds)){
            return true;
        }
        return false;
    }

////// End Controller	
}
