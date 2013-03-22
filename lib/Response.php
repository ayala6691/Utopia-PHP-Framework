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

class Response {
	
	const _CONTENT_TYPE_TEXT 		= 'Content-Type: text/html; charset=UTF-8';
	const _CONTENT_TYPE_JSON 		= 'Content-Type: application/json; charset=UTF-8';
	const _CONTENT_TYPE_XML	 		= 'Content-Type: text/xml; charset=UTF-8';
	const _CONTENT_TYPE_JAVASCRIPT	= 'Content-Type: text/javascript; charset=UTF-8';

	/**
	 * @var array
	 */
	private $statusCodes = Array(  
		0	=> 'Unknown',
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => '(Unused)',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);
	
	/**
	 * @var string
	 */
	private $contentType = self::_CONTENT_TYPE_TEXT;
	
	/**
	 * @var array
	 */
	private $headers = array();
	
	/**
	 * @var array
	 */
	private $cookies = array();
	
	/**
	 * @param string $type
	 * @return Response
	 */
	public function setContentType($type) {
		$this->contentType = $type;
		return $this;
	}
	
	/**
	 * @param int $code
	 * @return Response
	 */
	public function setStatusCode($code = 200) {
		if (!array_key_exists($code, $this->statusCodes)) {
			$code = 0;
		}
		
		$header = 'HTTP/1.1 ' . $code . ' ' . $this->statusCodes[$code];
		$this->addHeader($header);
		
		return $this;
	}
	
	/**
	 * @param string $value
	 * @param bool $exit [optional]
	 * @return Response
	 */
	public function addHeader($value, $exit = false){
		$this->headers[] = array('value' => $value, 'exit' => $exit);
		return $this;
	}
	
	/**
	 * @param string $name
	 * @param string $value 	[optional]
	 * @param int $expire 		[optional]
	 * @param string $path 		[optional]
	 * @param string $domain 	[optional]
	 * @param bool $secure 		[optional]
	 * @param bool $httponly 	[optional]
	 */
	public function addCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null) {
		$this->cookies[] = array(
			'name'		=> $name,
			'value'		=> $value,
			'expire'	=> $expire,
			'path' 		=> $path,
			'domain' 	=> $domain,
			'secure' 	=> $secure,
			'httponly'	=> $httponly,
		);
		
		return $this;
	}
	
	/**
	 * Output response headers and body
	 * 
	 * @param string $body
	 */
	public function send($body = '') {
		$this->appendCookies()->appendHeaders();
		echo $body;
	}
	
	/**
	 * @return Response
	 */
	private function appendHeaders() {
		
		header($this->contentType);

		foreach ($this->headers as $header){
			header($header['value']);
			
			if ($header['exit']){
				exit();
			}
		}
		
		return $this;
	}
	
	/**
	 * @return Response
	 */
	private function appendCookies() {
		
		foreach ($this->cookies as $cookie){
			setcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
		}
		
		return $this;
	}
}