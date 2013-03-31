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

class Request {
	
    const _METHOD_OPTIONS = 'OPTIONS';
    const _METHOD_GET     = 'GET';
    const _METHOD_HEAD    = 'HEAD';
    const _METHOD_POST    = 'POST';
    const _METHOD_PUT     = 'PUT';
    const _METHOD_DELETE  = 'DELETE';
    const _METHOD_TRACE   = 'TRACE';
    const _METHOD_CONNECT = 'CONNECT';
    
	/**
	 * @var array
	 */
	private $storage = array();

	/**
	 * @var array
	 */
	private $inputs = null;
	
	/**
	 * @var array
	 */
	private $headers = null;
	
	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setStorage($key, $value) {
		$this->storage[$key] = $value;
		return $this;
	}
	
	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getQuery($key, $default = null) {
		return (isset($_GET[$key])) ? $_GET[$key] : $default;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getPost($key, $default = null) {
		return (isset($_POST[$key])) ? $_POST[$key] : $default;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getRequest($key, $default = null) {
		return (isset($_REQUEST[$key])) ? $_REQUEST[$key] : $default;
	}
	
	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getServer($key, $default = null) {
		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}
	
	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getCookie($key, $default = null) {
		return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
	}
	
	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getStorage($key, $default = null) {
		return (isset($this->storage[$key])) ? $this->storage[$key] : $default;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getInput($key, $default = null) {
		$inputs = $this->generateInputs();
		return (isset($inputs[$key])) ? $inputs[$key] : $default;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getHeader($key, $default = null) {
		$headers = $this->generateHeaders();
		return (isset($headers[$key])) ? $headers[$key] : $default;
	}
	
	/**
	 * @param string $method
	 * @return array:
	 */
	public function getMethodParams() {
		switch ($this->getServer('REQUEST_METHOD', self::_METHOD_GET)) {
			case self::_METHOD_GET:
				return $_GET;
			break;
			
			case self::_METHOD_POST:
				return $this->generateInputs();
			break;
			
			default:
				return $this->generateInputs();
			break;
		}
	}

	/**
	 * @return array
	 */
	private function generateInputs() {
		if (null === $this->inputs) {
			$requestInput = file_get_contents('php://input');

			switch ($this->getHeader('Content-Type')) {
				case 'application/json;charset=UTF-8':
				case 'application/json':
					$this->inputs = json_decode($requestInput, true);
				break;
				
				default:
					parse_str($requestInput, $this->inputs);
				break;
			}
		}
	
		return $this->inputs;
	}

	/**
	 * @return array
	 */
	private function generateHeaders() {
		if (null === $this->headers) {
			$this->headers = getallheaders();
		}
	
		return $this->headers;
	}
}