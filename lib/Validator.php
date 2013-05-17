<?php
/**
 * Utopia PHP Framework
 *
 * @package Framework
 * @subpackage Core
 * 
 * @link https://github.com/eldadfux/Utopia-PHP-Framework
 * @author Eldad Fux <eldad@fuxie.co.il>
 * @version 1.0 RC4
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia;
//FIXME i need to rewrite this, this concept is wrong
class Validator { 
	
	const _FILTER_REQUIRED 	= 'Required';
	const _FILTER_STRING 	= 'String';
	const _FILTER_INTEGER 	= 'Intger';
	const _FILTER_EMAIL 	= 'Email';
	const _FILTER_IP	 	= 'Ip';
	
	/**
	 * @var array
	 */	
	private $rules = array();
	
	/**
	 * @var array
	 */
	private $errors = array();
	
	/**
	 * @var array
	 */
	private $data = array();
	
	/**
	 * @var mixed
	 */
	private $isValid = null;
	
	/**
	 * @param array $data
	 */
	public function __construct(array $data) {
		$this->data = $data;
	}
	
	/**
	 * @param string $field
	 * @param string $type
	 * @param array $options
	 */
	public function addRule($field, $type, array $options = array()) {
		$this->rules[] = array(
			'field' 	=> $field,
			'type'		=> $type,
			'options'	=> $options,
		);
		
		return $this;
	}

	/**
	 * if user input is valid return data else return false
	 * @return mixed the filtered data, or false if the filter fails.
	 */
	public function getFilterd() {	
		if ($this->isValid === null) {
			//Processing rules and filter data
			foreach ($this->rules as $rule) {
				$method = 'filter' . $rule['type'];
				$this->data[$rule['field']] = $this->$method($this->data[$rule['field']], $rule['options']);
			}
			
			$this->isValid = (empty($this->errors)) ? true : false;
		}
		
		return ($this->isValid) ? $this->data : false;
	}
	
	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * @param string $value
	 * @param array $options
	 */
	private function filterString($value, array $options = array()) {
		$defaults = array(
			'html'	=> false,
		);
		
		$options = $this->mergeOptions($options, $defaults);
		
		/* Special chars filter */
		if (!$options['html']){
			$value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
		
		return $value;
	}
	
	/**
	 * @param string $value
	 * @param array $options
	 */	
	private function filterEmail($value, array $options = array()) {
		$defaults = array(
			'error_code_1'	=> 'Invalid email address',
		);
		
		$options = $this->mergeOptions($options, $defaults);
		
		if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
			$this->errors[$options['error_code_1']] = true;
			return false;
		}

		return $value;
	}
		
	/**
	 * @param string $value
	 * @param array $options
	 */	
	private function filterRequired($value, array $options = array()) {
		$defaults = array(
			'error_code_1'	=> 'Missing required field',
		);
		
		$options = $this->mergeOptions($options, $defaults);
		
		if (empty($value)) {
			$this->errors[$options['error_code_1']] = true;
			return false;
		}

		return $value;
	}
	
	/**
	 * @param string $value
	 * @param array $options
	 */	
	private function filterIp($value, array $options = array()) {
		$defaults = array(
			'protocol' 		=> FILTER_FLAG_IPV4,
			'error_code_1'	=> 'Invalid IP address',
		);
		
		$options = $this->mergeOptions($options, $defaults);
		
		if (!filter_var($value, FILTER_VALIDATE_IP, $options['protocol'])) {
			$this->errors[$options['error_code_1']] = true;
			return false;
		}

		return $value;
	}
	
	/**
	 * @param array $options
	 * @param array $defaults
	 */	
	private function mergeOptions($options, $defaults) {
		return array_merge($options, $defaults);
	}
}