<?php

/* (c)Admin Module Bittion | Created: 13/09/2014 | Developer:reyro | Component: CurrentApp | Description: universal function for this current app */

/**
 * Description of CurrentAppComponent
 *
 * @author rey
 */
class CurrentAppComponent extends Component {

    //  Created: 13/09/2014 | Developer: reyro | Description: get last exrate change
    public function fnCurrentExrateValue($currency = 'USD') {
        $this->InvExchangeRate = ClassRegistry::init('InvExchangeRate');
        try {
            $response = $this->InvExchangeRate->find('first', array('order' => array('InvExchangeRate.id' => 'DESC'), 'fields' => array('InvExchangeRate.value')));
            return $response['InvExchangeRate']['value'];
        } catch (Exception $exc) {
            return '';
        }
    }

    //  Created: 13/09/2014 | Developer: reyro | Description: create document's codes
    public function fnGenerateCode($keyword, $model) {
        $this->$model = ClassRegistry::init($model);

        $last = $this->$model->find('first', array('fields' => array($model . '.system_code'), 'order' => array($model . '.id' => 'DESC')));
        if (!isset($last[$model]['system_code'])) {
            return $keyword . '-0000001';
        }

        $divided = explode('-', $last[$model]['system_code']);
        $number = (int) $divided[1];
        $nextNumber = (string)($number + 1);
        $chars = strlen($nextNumber);
        $zerosQuantity = 7 - $chars;
        $zeros = '';
        for ($index = 0; $index < $zerosQuantity; $index++) {
            $zeros .= '0';
        }
        $codeNumber = $zeros . $nextNumber;
        return $keyword . '-' . $codeNumber;
    }

}
