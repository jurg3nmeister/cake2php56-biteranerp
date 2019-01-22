<?php

/* (c)Bittion | Created: 18/09/2014 | Developer:reyro | Controller: InvProducts */

App::uses('AppController', 'Controller');

class InvProductsController extends AppController {

//*****************************START CONTROLLERS ********************************
////////////////////////VIEWS
//    Created: 11/10/2014 | Developer: reyro | Description: Save product (create and update)
    public function save() {
        //************************* Post Upload Image *******************//
        if ($this->request->is(array('post', 'put'))) {

            $iWidth = 450;
            $iHeight = 450; // desired image result dimensions
            $iJpgQuality = 100;

            if ($_FILES) {
                // if no errors and size less than 250kb
                if (! $_FILES['data']['error']['InvProduct']['picture'] && $_FILES['data']['size']['InvProduct']['picture'] < 250 * 1024) {
                    if (is_uploaded_file($_FILES['data']['tmp_name']['InvProduct']['picture'])) {

                        // new unique filename
                        $sTempFileName = 'img/products/' . md5(time().rand());

                        // move uploaded file into images folder
                        move_uploaded_file($_FILES['data']['tmp_name']['InvProduct']['picture'], $sTempFileName);

                        // change file permission to 644
                        @chmod($sTempFileName, 0644);

                        if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                            $aSize = getimagesize($sTempFileName); // try to obtain image info
                            if (!$aSize) {
                                @unlink($sTempFileName);
                                return;
                            }

                            // check for image type
                            switch($aSize[2]) {
                                case IMAGETYPE_JPEG:
                                    $sExt = '.jpg';

                                    // create a new image from file
                                    $vImg = @imagecreatefromjpeg($sTempFileName);
                                    break;
                                /*case IMAGETYPE_GIF:
                                    $sExt = '.gif';

                                    // create a new image from file
                                    $vImg = @imagecreatefromgif($sTempFileName);
                                    break;*/
                                case IMAGETYPE_PNG:
                                    $sExt = '.png';

                                    // create a new image from file
                                    $vImg = @imagecreatefrompng($sTempFileName);
                                    break;
                                default:
                                    @unlink($sTempFileName);
                                    return;
                            }

                            // create a new true color image
                            $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );

                            // copy and resize part of an image with resampling
                            imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$_POST['x1'], (int)$_POST['y1'], $iWidth, $iHeight, (int)$_POST['w'], (int)$_POST['h']);

                            // define a result image filename
                            $sResultFileName = $sTempFileName . $sExt;

                            // output image to file
                            imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                            @unlink($sTempFileName);
                            $data = null;

                            //This code works with the ajax_save function. Using this because  window.history.pushState(... & passedArgs doesn't work on post (weird)
                            if($this->request->data['InvProduct']['id_2'] != ''){
                                $data['InvProduct']['id'] = $this->request->data['InvProduct']['id_2'];
                                $imgName = $this->InvProduct->find('list', array('fields' => array('InvProduct.picture'), 'conditions' => array('InvProduct.id' => $data['InvProduct']['id'])));
                                if(current($imgName) != null){
                                    @unlink('img/products/'.current($imgName)); // delete previous image
                                }
                                $image = substr($sResultFileName, 13);
                                $data['InvProduct']['picture'] = $image;
                                $response = $this->_fnUpdate($data);
                                if($response['status']=='SUCCESS'){
                                    $this->redirect(array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
//									$this->flash('La imagen se grabo con exito.', array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));	//Needs Configure::write('debug', 0); to work
                                }else{
                                    $this->redirect(array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
//									$this->flash('La imagen no se pudo grabar.', array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));	//Needs Configure::write('debug', 0);
                                }
                            }

                            //This code doesn't work properly with the ajax save form
                            /*						if (isset($this->passedArgs['id'])) {//UPDATE PICTURE
                                                        $imgName = $this->InvProduct->find('list', array('fields' => array('InvProduct.picture'), 'conditions' => array('InvProduct.id' => $this->passedArgs['id'])));
                                                        if(current($imgName) != null){
                                                            @unlink('img/products/'.current($imgName)); // delete previous image
                                                        }
                                                        $data['InvProduct']['id'] = $this->passedArgs['id'];
                                                        $image = substr($sResultFileName, 13);
                                                        $data['InvProduct']['picture'] = $image;
                                                        $response = $this->_fnUpdate($data);
                                                        if($response['status']=='SUCCESS'){
                                                            $this->redirect(array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
                                                        }else{
                                                            $this->redirect(array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
                                                        }
                                                    }*/
                        }
                    }
                }
            }
        }
        //************************* Post Upload Image *******************//
        $image = 'no-image.jpg';  //default no-image
        $id_2 = '';  //for the form file upload image
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->InvProduct->id = $this->passedArgs['id'];
            if (!$this->InvProduct->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            //Check if is not recipe
            $checkRecipe = $this->InvProduct->find('count', array('conditions'=>array('InvProduct.recipe'=>1, 'InvProduct.id'=>$this->passedArgs['id'])));
            if($checkRecipe == 1){
                $this->redirect(array('action' => 'index'));
            }

            $this->InvProduct->recursive = -1;
            $this->request->data = $this->InvProduct->read(null, $this->passedArgs['id']);
            $this->request->data['InvPrice'][0]['price'] = $this->request->data['InvProduct']['current_price'];
            //To solve select doesn't recognize true false because select value is 1 & 0
            $active = 0;
            if($this->request->data['InvProduct']['website']){
                $active = 1;
            }
            $this->request->data['InvProduct']['website'] = $active;

            if($this->request->data['InvProduct']['picture'] != null){
                $image = $this->request->data['InvProduct']['picture'];
            } else {
                $image = 'no-image.jpg';
            }
            $id_2 = $this->passedArgs['id'];
        }

        $this->set('id_2', $id_2);
        $brands = $this->InvProduct->InvBrand->find('list', array('conditions'=>array('InvBrand.id !='=>4)));
        $categories = $this->InvProduct->InvCategory->find('list', array('conditions'=>array('InvCategory.id !='=>3)));
        $booleans = array(0 => 'No', 1 => 'Si');
        $this->set(compact('brands', 'categories', 'booleans', 'image'));
    }

//    Created: 06/06/2015 | Developer: reyro | Description: Save product with recipe (create and update)
    public function save_with_recipe() {
        //************************* Post Upload Image *******************//
        if ($this->request->is(array('post', 'put'))) {

            $iWidth = 450;
            $iHeight = 450; // desired image result dimensions
            $iJpgQuality = 100;

            if ($_FILES) {
                // if no errors and size less than 250kb
                if (! $_FILES['data']['error']['InvProduct']['picture'] && $_FILES['data']['size']['InvProduct']['picture'] < 250 * 1024) {
                    if (is_uploaded_file($_FILES['data']['tmp_name']['InvProduct']['picture'])) {

                        // new unique filename
                        $sTempFileName = 'img/products/' . md5(time().rand());

                        // move uploaded file into images folder
                        move_uploaded_file($_FILES['data']['tmp_name']['InvProduct']['picture'], $sTempFileName);

                        // change file permission to 644
                        @chmod($sTempFileName, 0644);

                        if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                            $aSize = getimagesize($sTempFileName); // try to obtain image info
                            if (!$aSize) {
                                @unlink($sTempFileName);
                                return;
                            }

                            // check for image type
                            switch($aSize[2]) {
                                case IMAGETYPE_JPEG:
                                    $sExt = '.jpg';

                                    // create a new image from file
                                    $vImg = @imagecreatefromjpeg($sTempFileName);
                                    break;
                                case IMAGETYPE_PNG:
                                    $sExt = '.png';

                                    // create a new image from file
                                    $vImg = @imagecreatefrompng($sTempFileName);
                                    break;
                                default:
                                    @unlink($sTempFileName);
                                    return;
                            }

                            // create a new true color image
                            $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );

                            // copy and resize part of an image with resampling
                            imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$_POST['x1'], (int)$_POST['y1'], $iWidth, $iHeight, (int)$_POST['w'], (int)$_POST['h']);

                            // define a result image filename
                            $sResultFileName = $sTempFileName . $sExt;

                            // output image to file
                            imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                            @unlink($sTempFileName);
                            $data = null;

                            //This code works with the ajax_save function. Using this because  window.history.pushState(... & passedArgs doesn't work on post (weird)
                            if($this->request->data['InvProduct']['id_2'] != ''){
                                $data['InvProduct']['id'] = $this->request->data['InvProduct']['id_2'];
                                $imgName = $this->InvProduct->find('list', array('fields' => array('InvProduct.picture'), 'conditions' => array('InvProduct.id' => $data['InvProduct']['id'])));
                                if(current($imgName) != null){
                                    @unlink('img/products/'.current($imgName)); // delete previous image
                                }
                                $image = substr($sResultFileName, 13);
                                $data['InvProduct']['picture'] = $image;
                                $response = $this->_fnUpdate($data);
                                if($response['status']=='SUCCESS'){
                                    $this->redirect(array('action' => 'save_with_recipe', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
//									$this->flash('La imagen se grabo con exito.', array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));	//Needs Configure::write('debug', 0); to work
                                }else{
                                    $this->redirect(array('action' => 'save_with_recipe', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));
//									$this->flash('La imagen no se pudo grabar.', array('action' => 'save', 'id' => $data['InvProduct']['id'] ,'#' => 's2'));	//Needs Configure::write('debug', 0);
                                }
                            }
                        }
                    }
                }
            }
        }
        //************************* Post Upload Image *******************//
        $image = 'no-image.jpg';  //default no-image
        $id_2 = '';  //for the form file upload image
        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->InvProduct->id = $this->passedArgs['id'];
            if (!$this->InvProduct->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            //Check if is recipe
            $checkRecipe = $this->InvProduct->find('count', array('conditions'=>array('InvProduct.recipe'=>1, 'InvProduct.id'=>$this->passedArgs['id'])));
            if($checkRecipe == 0){
                $this->redirect(array('action' => 'index'));
            }

            $this->InvProduct->recursive = -1;
            $this->request->data = $this->InvProduct->read(null, $this->passedArgs['id']);
            $this->request->data['InvPrice'][0]['price'] = $this->request->data['InvProduct']['current_price'];
            //To solve select doesn't recognize true false because select value is 1 & 0
            $active = 0;
            if($this->request->data['InvProduct']['website']){
                $active = 1;
            }
            $this->request->data['InvProduct']['website'] = $active;

            if($this->request->data['InvProduct']['picture'] != null){
                $image = $this->request->data['InvProduct']['picture'];
            } else {
                $image = 'no-image.jpg';
            }
            $id_2 = $this->passedArgs['id'];
        }

        $this->set('id_2', $id_2);
        $brands = $this->InvProduct->InvBrand->find('list');
        $categories = $this->InvProduct->InvCategory->find('list');
        $booleans = array(0 => 'No', 1 => 'Si');
        $this->set(compact('brands', 'categories', 'booleans', 'image'));
    }

//    Created: 10/04/2015 | Developer: reyro | Description: Save service (create and update)
    public function save_service() {

        if (isset($this->passedArgs['id'])) {//UPDATE
            $this->InvProduct->id = $this->passedArgs['id'];
            if (!$this->InvProduct->exists()) {
                $this->redirect(array('action' => 'index'));
            }
            $this->InvProduct->recursive = -1;
            $this->request->data = $this->InvProduct->read(null, $this->passedArgs['id']);
            $this->request->data['InvPrice'][0]['price'] = $this->request->data['InvProduct']['current_price'];
            //To solve select doesn't recognize true false because select value is 1 & 0
            $active = 0;

            if($this->request->data['InvProduct']['website']){
                $active = 1;
            }
            $this->request->data['InvProduct']['website'] = $active;
        }

//        $brands = $this->InvProduct->InvBrand->find('list');
//        $categories = $this->InvProduct->InvCategory->find('list');
//        $measures = $this->InvProduct->find('list', array('fields' => array('InvProduct.measure', 'InvProduct.measure'), 'group' => array('InvProduct.measure')));
        $booleans = array(0 => 'No', 1 => 'Si');
        $this->set(compact('booleans'));
    }

//    Created: 18/09/2014 | Developer: reyro | Description: View Index related with fnRead() 	
    public function index() {
        
    }

//    Created: 10/04/2015 | Developer: reyro | Description: View services fnReadServices()
    public function services() {

    }
////////////////////////PUBLIC FUNCTIONS
//    Created: 11/10/2014 | Developer: reyro | Description: Function save (create and update) | Request: Ajax    
    public function fnSave() {
        if ($this->RequestHandler->isAjax()) {
            $response = '';
            $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
            if ($currentExrate == '') {
                return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'error al obtener el cambio de moneda')))));
            }
            $data = $this->request->data['data'];
            $data['InvProduct']['code'] = strtoupper($data['InvProduct']['code']);
            $data['InvPrice'][0]['ex_rate'] = $currentExrate;
            $data['InvProduct']['current_price'] = $data['InvPrice'][0]['price'];
            $data['InvProduct']['service'] = 0; //default 0(false) for product
            if (isset($data['InvProduct']['id']) && $data['InvProduct']['id'] != '') { //UPDATE
                $response = $this->_fnUpdate($data);
            } else {//CREATE
                $response = $this->_fnCreate($data);
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Function save (create and update) | Request: Ajax
    public function fnSaveIngredient() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            if(isset($this->request->data['InvRecipeId'])){
                $data['InvRecipe']['id'] = $this->request->data['InvRecipeId'];
            }
            $data['InvRecipe']['inv_product_id'] = $this->request->data['productId'];
            $data['InvRecipe']['inv_product_ingredient_id'] = $this->request->data['productIngredientId'];
            $data['InvRecipe']['quantity'] = $this->request->data['quantity'];
            $this->loadModel('InvRecipe');
            if ($this->InvRecipe->save($data)) {
                $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array()));
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/04/2015 | Developer: reyro | Description: Function save services (create and update) | Request: Ajax
    public function fnSaveService() {
        if ($this->RequestHandler->isAjax()) {
            $response = '';
            $currentExrate = $this->CurrentApp->fnCurrentExrateValue();
            if ($currentExrate == '') {
                return new CakeResponse(array('body' => json_encode($this->BittionMain->fnGetMethodResponse('ERROR', array('content' => 'error al obtener el cambio de moneda')))));
            }
            $data = $this->request->data['data'];
            $data['InvProduct']['code'] = strtoupper($data['InvProduct']['code']);
            $data['InvPrice'][0]['ex_rate'] = $currentExrate;
            $data['InvProduct']['current_price'] = $data['InvPrice'][0]['price'];
            ///////
            $data['InvProduct']['inv_category_id'] = 3; //default id for services
            $data['InvProduct']['inv_brand_id'] = 4; //default id for services
            $data['InvProduct']['service'] = 1; //default 1(true) for service
            //////
            if (isset($data['InvProduct']['id']) && $data['InvProduct']['id'] != '') { //UPDATE
                $response = $this->_fnUpdate($data);
            } else {//CREATE
                $response = $this->_fnCreate($data);
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 18/09/2014 | Developer: reyro | Description: Function Read for DataTable | Request: Ajax 	
    public function fnRead() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'InvProduct';
            //Virtual field subquery (witouth this can't sort
            $this->InvProduct->virtualFields['category'] = '(SELECT name FROM inv_categories WHERE id = "InvProduct"."inv_category_id")';
            $this->InvProduct->virtualFields['brand'] = '(SELECT name FROM inv_brands WHERE id = "InvProduct"."inv_brand_id")';
            
            $fields = array($model . '.recipe', $model . '.code', $model . '.picture', $model . '.name', /*$model . '.measure',*/ 'InvProduct.category', 'InvProduct.brand', $model . '.current_price', $model.'.website', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower(' . $model . '.measure) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower((SELECT name FROM inv_categories WHERE id = "InvProduct"."inv_category_id")) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower((SELECT name FROM inv_brands WHERE id = "InvProduct"."inv_brand_id")) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.current_price AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR , 'InvCategory.id !='=>3, 'InvBrand.id !='=>4);
            /////////////////////////////////////
//            $this->$model->recursive = -1;
            $this->InvProduct->unbindModel(array('hasMany' => array('InvRecipe'))); //no used for index products
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
            $total = $this->$model->find('count', array(
                'conditions' => $conditions
            ));
//            $total = $this->params['paging'][$model]['pageCount']; //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                if($value[$model]['picture'] == ""){
                    $value[$model]['picture'] = "no-image.jpg";
                }
                $array['aaData'][$key][0] = $value[$model]['picture'];
                $array['aaData'][$key][1] = $value[$model]['code'];
                $array['aaData'][$key][2] = $value[$model]['name'];
//                $array['aaData'][$key][2] = $value[$model]['measure'];
                $array['aaData'][$key][3] = $value[$model]['category'];
                $array['aaData'][$key][4] = $value[$model]['brand'];
                $array['aaData'][$key][5] = $value[$model]['current_price'];
                $array['aaData'][$key][6] = $value[$model]['website'];
                $array['aaData'][$key][7] = $value[$model]['id']; //for edit and delete buttons

                $array['aaData'][$key][8] = $value[$model]['recipe'];
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 10/04/2015 | Developer: reyro | Description: Function Read Services for DataTable | Request: Ajax
    public function fnReadServices() {
        if ($this->RequestHandler->isAjax()) {
            //Variables
            $model = 'InvProduct';
            //Virtual field subquery (witouth this can't sort
//            $this->InvProduct->virtualFields['category'] = '(SELECT name FROM inv_categories WHERE id = "InvProduct"."inv_category_id")';
//            $this->InvProduct->virtualFields['brand'] = '(SELECT name FROM inv_brands WHERE id = "InvProduct"."inv_brand_id")';

            $fields = array($model . '.code', $model . '.name', $model . '.current_price', $model.'.website', $model . '.id');
            $conditionsOR = array(
                'lower(' . $model . '.code) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'lower(' . $model . '.name) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower((SELECT name FROM inv_categories WHERE id = "InvProduct"."inv_category_id")) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
//                'lower((SELECT name FROM inv_brands WHERE id = "InvProduct"."inv_brand_id")) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
                'CAST(' . $model . '.current_price AS TEXT) LIKE' => '%' . strtolower($this->request->data['sSearch']) . '%',
            );
            $conditions = array('OR' => $conditionsOR, 'InvCategory.id'=>3, 'InvBrand.id'=>4);  //Category and Brand ids for services
            /////////////////////////////////////
//            $this->$model->recursive = -1;
            $this->paginate = array(
                'order' => array($fields[$this->request->data['iSortCol_0']] => $this->request->data['sSortDir_0']),
                'limit' => $this->request->data['iDisplayLength'],
                'offset' => $this->request->data['iDisplayStart'],
                'fields' => $fields,
                'conditions' => $conditions
            );
            $arrayPaginate = $this->paginate();
            $total = $this->$model->find('count', array(
                'conditions' => $conditions
            ));
//            $total = $this->params['paging'][$model]['pageCount']; //doesn't work
            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            foreach ($arrayPaginate as $key => $value) {
                //Set datatable columns by column number
                $array['aaData'][$key][0] = $value[$model]['code'];
                $array['aaData'][$key][1] = $value[$model]['name'];
                $array['aaData'][$key][2] = $value[$model]['current_price'];
                $array['aaData'][$key][3] = $value[$model]['website'];
                $array['aaData'][$key][4] = $value[$model]['id']; //for edit and delete buttons
            }
            $array['iTotalRecords'] = $total;
            $array['iTotalDisplayRecords'] = $total;
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 03/05/2015 | Developer: reyro | Description: Read product price| Request: Ajax
    public function fnReadProductPrices() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('InvProduct');
                $lastPrice = $this->InvProduct->InvPrice->find('first', array('conditions' => array('InvPrice.inv_product_id' => $this->request->data['productId']), 'order' => array('InvPrice.id' => 'DESC'), 'fields' => array('InvPrice.price')));
                $response = array('lastPrice' => $lastPrice['InvPrice']['price']);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/07/2015 | Developer: reyro | Description: Read product price| Request: Ajax
    public function fnReadIngredientUpdate(){
        if ($this->RequestHandler->isAjax()) {

            $this->loadModel('InvRecipe');
            $this->InvRecipe->virtualFields['purchase_price'] = '(SELECT purchase_price FROM inv_products WHERE id = "InvRecipe"."inv_product_ingredient_id")';
            $this->InvRecipe->virtualFields['product_name'] = '(SELECT name FROM inv_products WHERE id = "InvRecipe"."inv_product_ingredient_id")';
            $this->InvRecipe->virtualFields['sale_price'] = '(SELECT price FROM inv_prices WHERE inv_product_id = "InvRecipe"."inv_product_ingredient_id" ORDER BY id DESC LIMIT 1)';

            $ingredient = $this->InvRecipe->find('all', array(
                'conditions'=>array('InvRecipe.id'=>$this->request->data['id']),
                'fields'=>array('InvRecipe.inv_product_ingredient_id', 'InvRecipe.purchase_price', 'InvRecipe.quantity', 'InvRecipe.sale_price', 'InvRecipe.product_name')
            ));

            $response = array('productName'=>$ingredient[0]['InvRecipe']['product_name'], 'salePrice'=>$ingredient[0]['InvRecipe']['sale_price'], 'purchasePrice'=>$ingredient[0]['InvRecipe']['purchase_price'], 'quantity'=>$ingredient[0]['InvRecipe']['quantity'], 'ingredientId'=>$ingredient[0]['InvRecipe']['inv_product_ingredient_id']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Function Read Recipe Ingredients for DataTable WITHOUT PAGINATION| Request: Ajax
    public function fnReadIngredients() {
        if ($this->RequestHandler->isAjax()) {

            $this->loadModel('InvRecipe');
            $ingredients = $this->InvRecipe->find('all', array('conditions'=>array('InvRecipe.inv_product_id'=>$this->request->data['id'])));
            foreach ($ingredients as $key => $value) {
                $thisProducts[$value['InvRecipe']['inv_product_ingredient_id']] = $value['InvRecipe']['inv_product_ingredient_id'];
            }


            $array = array('sEcho' => $this->request->data['sEcho']);
            $array['aaData'] = array();
            //If is empty return empty array to avoid datatable errors
            if(count($ingredients) == 0){
                return new CakeResponse(array('body' => json_encode($array)));
            }
    //////////////////////////////

            $this->InvProduct->virtualFields['last_price'] = '(SELECT price FROM inv_prices WHERE inv_product_id = "InvProduct"."id" ORDER BY id DESC LIMIT 1)';
            $products = $this->InvProduct->find('all', array(
                'conditions'=>array('InvProduct.id'=>$thisProducts),
                'fields' => array('InvProduct.code', 'InvProduct.name', 'InvProduct.purchase_price', 'InvProduct.last_price', 'InvProduct.id')
            ));
            /////////////////////////////////////

            $counter = $this->request->data['iDisplayStart'] + 1;

            foreach ($ingredients as $keyI => $valueI) {
                foreach ($products as $keyP => $valueP) {
                    if($valueI['InvRecipe']['inv_product_ingredient_id'] == $valueP['InvProduct']['id']){
                        //Set datatable columns by column number
                        $array['aaData'][$keyI][0] = $counter;
                        $array['aaData'][$keyI][1] = $valueP['InvProduct']['code'];
                        $array['aaData'][$keyI][2] = $valueP['InvProduct']['name'];
                        $array['aaData'][$keyI][3] = $valueP['InvProduct']['purchase_price'];
                        $array['aaData'][$keyI][4] = $valueP['InvProduct']['last_price']; //sale_price
                        $array['aaData'][$keyI][5] = $valueI['InvRecipe']['quantity'];
                        $array['aaData'][$keyI][6] = number_format($valueI['InvRecipe']['quantity'] * $valueP['InvProduct']['last_price'], 2, '.', '');
                        $array['aaData'][$keyI][7] = $valueI['InvRecipe']['id'];
                        $counter++;
                    }
                }

            }
            return new CakeResponse(array('body' => json_encode($array)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 18/09/2014 | Developer: reyro | Description: Function Delete| Request: Ajax 	
    public function fnDelete() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            //Check if it was used by sales
            $sales = $this->InvProduct->SalSalesDetail->find('count', array('conditions' => array('SalSalesDetail.inv_product_id' => $this->request->data['id'])));
            if ($sales > 0) {
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada', 'content' => 'No se puede eliminar, ya fue usado para VENTAS'));
                return new CakeResponse(array('body' => json_encode($response)));
            }

            //Check if it's recipe
            $this->loadModel('InvRecipe');
            $itsPartOfRecipe = $this->InvRecipe->find('count', array(
                'conditions'=>array('InvRecipe.inv_product_ingredient_id'=>$this->request->data['id'])
            ));
            if($itsPartOfRecipe > 0){
                $response = $this->BittionMain->fnGetMethodResponse('ERROR', array('title' => 'Acción denegada', 'content' => 'No se puede eliminar, es parte de un producto ensamblado'));
                return new CakeResponse(array('body' => json_encode($response)));
            }

            //If it's recipe type => delete ingredients too.
            $itsRecipe = $this->InvProduct->find('count', array(
                'conditions'=>array('InvProduct.id'=>$this->request->data['id'], 'InvProduct.recipe'=>1)
            ));
            if($itsRecipe == 1){
                //delete in transaction model
                $response = $this->InvProduct->fnDeleteAndRecipe($this->request->data['id']);
                return new CakeResponse(array('body' => json_encode($response)));
            }
            ////////////////////////////////////////////////////////////////////////////////////////////////
            $response = $this->InvProduct->fnDelete($this->request->data['id']);
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 12/10/2014 | Developer: reyro | Description: verify unique code
    public function fnVerifyUniqueCode() {
        if ($this->RequestHandler->isAjax()) {
            $code = $this->request->data['value'];
            $id = '';
            if(isset($this->request->data['id'])){
                $id = $this->request->data['id'];
            }
            $response = $this->InvProduct->find('count', array(
                'conditions' => array('InvProduct.code' => $code, 'NOT'=>array('InvProduct.id'=>$id)),
                'recursive' => -1
            ));
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }


//    Created: 06/06/2015 | Developer: reyro | Description: Read products| Request: Ajax
    public function fnReadProducts() {
        if ($this->RequestHandler->isAjax()) {
            $conditions = null;
            if ($this->request->data['id'] != '') { //EXISTS
                $this->loadModel('InvRecipe');
                $productDetailIds = $this->InvRecipe->find('list', array(
                    'fields' => array('InvRecipe.inv_product_ingredient_id', 'InvRecipe.inv_product_ingredient_id'),
                    'conditions' => array('InvRecipe.inv_product_id' => $this->request->data['id'])
                ));
                $conditions = array(
                    'NOT' => array('InvProduct.id' => array_merge($productDetailIds, array($this->request->data['id']=>$this->request->data['id']))),
                    'InvProduct.service'=>0
                );
            }
            try {
                $this->loadModel('InvProduct');
                $products = $this->InvProduct->find('list', array('conditions' => $conditions));
                $response = array('products' => $products);
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Read Modal Add product price| Request: Ajax
    public function fnReadModalAddProductPrices() {
        if ($this->RequestHandler->isAjax()) {
            try {
                $this->loadModel('InvPrice');
                $lastPrice = $this->InvPrice->find('first', array('conditions' => array('InvPrice.inv_product_id' => $this->request->data['productId']), 'order' => array('InvPrice.id' => 'DESC'), 'fields' => array('InvPrice.price')));
                $response = array('salePrice' => $lastPrice['InvPrice']['price']);

                $this->loadModel('InvProduct');
                $purchasePrice = $this->InvProduct->find('first', array('conditions' => array('InvProduct.id' => $this->request->data['productId']), 'fields' => array('InvProduct.purchase_price', 'InvProduct.profit_percentage')));
                $profit = ($purchasePrice['InvProduct']['purchase_price'] * $purchasePrice['InvProduct']['profit_percentage']) /100;
                $response = array_merge($response, array('purchasePrice'=>$purchasePrice['InvProduct']['purchase_price'], 'profitPercentage'=>$purchasePrice['InvProduct']['profit_percentage'], 'profit'=>$profit));
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

//    Created: 07/06/2015 | Developer: reyro | Description: Function Delete Ingredient| Request: Ajax
    public function fnDeleteIngredient() {
        if ($this->RequestHandler->isAjax()) {
            $response = $this->BittionMain->fnGetMethodResponse('ERROR');
            $this->loadModel('InvRecipe');
            $this->InvRecipe->id = $this->request->data['id'];
            try {
                if ($this->InvRecipe->delete()) {
                    $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('content' => 'DELETE'));
                }
            } catch (Exception $exc) {
                $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
            }
            return new CakeResponse(array('body' => json_encode($response)));
        } else {
            $this->redirect($this->Auth->logout()); //only accesible through ajax otherwise logout
        }
    }

///////////////////////PRIVATE FUNCTIONS
//    Created: 11/10/2014 | Developer: reyro | Description: Private function Create | Request: Ajax
    private function _fnCreate($data) {
        $response = $this->BittionMain->fnGetMethodResponse('ERROR');
        $this->InvProduct->create();
        try {
            if ($this->InvProduct->saveAssociated($data)) {
                $measures = $this->InvProduct->find('list', array('fields' => array('InvProduct.measure', 'InvProduct.measure'), 'group' => array('InvProduct.measure')));
                $response = $this->BittionMain->fnGetMethodResponse('SUCCESS', array('data' => array('measures' => $measures, 'id' => $this->InvProduct->id)));
            }
        } catch (Exception $exc) {
            $response = $this->BittionMain->fnGetExceptionResponse($exc->getCode());
        }
        return $response;
    }

//    Created: 11/10/2014 | Developer: reyro | Description: Private function Update | Request: Ajax    
    private function _fnUpdate($data) {
        $response = $this->InvProduct->fnModelUpdate($data);
        return $response;
    }

    //***************************** END CONTROLLERS ********************************
}
