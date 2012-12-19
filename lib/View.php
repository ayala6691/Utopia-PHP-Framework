<?php
/**
 * Utopia PHP Framework
 *
 * @package Framework
 * @subpackage Core
 * 
 * @link http://code.google.com/p/utopia-php-framework/
 * @author Eldad Fux <eldad@fuxie.co.il>
 * @version 1.0 RC3
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia;

class View extends Plugin {

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @var bool
	 */
	protected $rendered = false;
	
	/**
	 * @var array
	 */
	protected $params = array();

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return View
	 */
	public function setParam($key, $value) {
		$this->params[$key] = $value;
		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed $default (optional)
	 * @return mixed
	 */
	public function getParam($key, $default = null) {
		return (isset($this->params[$key])) ? $this->params[$key] : $default;
	}
	
	/**
	 * @param string $path
	 * @throws Exception
	 * @return View
	 */
	public function setPath($path) {
		$this->path = $path;
		return $this;
	}
	
	/**
	 * @return View
	 */
	public function setRendered() {
		$this->rendered = true;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isRendered() {
		return (bool)$this->rendered;
	}
	
	/**
	 * @return string
	 */
	public function render() {
	
		if ($this->rendered) {
			return '';
		}

		ob_start(); //Start of build
		
		if (is_readable($this->path)){
			include $this->path; //Include View
		}
		else {
			//throw new \Exception($this->path . ' Action view is not readable');
		}
		
		$html = ob_get_contents();
		ob_end_clean(); //End of build
		
		return $html;
	}
	
	/* View Helpers -> this methods will be implemented as external files in feature version  */
	
	/**
	 * @param string $str
	 * @return string
	 */
	public function escape($str) {
		return htmlentities($str, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * @param string $filename
	 * @param int $type
	 */
	public function getMediaHost($filename, $type = 0) {
		if('' == $filename) {
			return '//' . $_SERVER['HTTP_HOST'] . '/images/404.gif';
		}
		
		return '//media.' . $_SERVER['HTTP_HOST'] . '/' . $type . '-' . $filename;
	}
	
	/**
	 * @param string $controller
	 * @param string $action
	 * @param mixed $data
	 */
	public function getAction($controller = 'default', $action = 'index', $data = null){
		return $this->getApp()->mvc->getAction(null, $controller, $action, $data);
	}
}