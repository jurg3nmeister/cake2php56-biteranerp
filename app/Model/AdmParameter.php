<?php /* (c)Bittion Admin Module | Created: 30/07/2014 | Developer:reyro */ ?>
<?php
App::uses('AppModel', 'Model');
/**
 * AdmParameter Model
 *
 */
class AdmParameter extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'parameter_key' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
