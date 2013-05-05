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

use Exception;

class Response {
	
	const _CONTENT_TYPE_HTML 		= 'text/html; charset=UTF-8';
	const _CONTENT_TYPE_JSON 		= 'application/json; charset=UTF-8';
	const _CONTENT_TYPE_XML	 		= 'text/xml; charset=UTF-8';
	const _CONTENT_TYPE_JAVASCRIPT	= 'text/javascript; charset=UTF-8';

	// HTTP status codes
	const _STATUS_CODE_CONTINUE 						= 100;
	const _STATUS_CODE_SWITCHING_PROTOCOLS 				= 101;
	const _STATUS_CODE_OK 								= 200;
	const _STATUS_CODE_CREATED 							= 201;
	const _STATUS_CODE_ACCEPTED 						= 202;
	const _STATUS_CODE_NON_AUTHORITATIVE_INFORMATION 	= 203;
	const _STATUS_CODE_NO_CONTENT 						= 204;
	const _STATUS_CODE_RESET_CONTENT 					= 205;
	const _STATUS_CODE_PARTIAL_CONTENT 					= 206;
	const _STATUS_CODE_MULTIPLE_CHOICES 				= 300;
	const _STATUS_CODE_MOVED_PERMANENTLY 				= 301;
	const _STATUS_CODE_FOUND							= 302;
	const _STATUS_CODE_SEE_OTHER 						= 303;
	const _STATUS_CODE_NOT_MODIFIED 					= 304;
	const _STATUS_CODE_USE_PROXY 						= 305;
	const _STATUS_CODE_UNUSED 							= 306;
	const _STATUS_CODE_TEMPORARY_REDIRECT 				= 307;
	const _STATUS_CODE_BAD_REQUEST 						= 400;
	const _STATUS_CODE_UNAUTHORIZED 					= 401;
	const _STATUS_CODE_PAYMENT_REQUIRED 				= 402;
	const _STATUS_CODE_FORBIDDEN 						= 403;
	const _STATUS_CODE_NOT_FOUND 						= 404;
	const _STATUS_CODE_METHOD_NOT_ALLOWED 				= 405;
	const _STATUS_CODE_NOT_ACCEPTABLE 					= 406;
	const _STATUS_CODE_PROXY_AUTHENTICATION_REQUIRED 	= 407;
	const _STATUS_CODE_REQUEST_TIMEOUT 					= 408;
	const _STATUS_CODE_CONFLICT 						= 409;
	const _STATUS_CODE_GONE 							= 410;
	const _STATUS_CODE_LENGTH_REQUIRED 					= 411;
	const _STATUS_CODE_PRECONDITION_FAILED 				= 412;
	const _STATUS_CODE_REQUEST_ENTITY_TOO_LARGE 		= 413;
	const _STATUS_CODE_REQUEST_URI_TOO_LONG 			= 414;
	const _STATUS_CODE_UNSUPPORTED_MEDIA_TYPE 			= 415;
	const _STATUS_CODE_REQUESTED_RANGE_NOT_SATISFIABLE 	= 416;
	const _STATUS_CODE_EXPECTATION_FAILED 				= 417;
	const _STATUS_CODE_INTERNAL_SERVER_ERROR 			= 500;
	const _STATUS_CODE_NOT_IMPLEMENTED 					= 501;
	const _STATUS_CODE_BAD_GATEWAY 						= 502;
	const _STATUS_CODE_SERVICE_UNAVAILABLE 				= 503;
	const _STATUS_CODE_GATEWAY_TIMEOUT 					= 504;
	const _STATUS_CODE_HTTP_VERSION_NOT_SUPPORTED 		= 505;
		
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
	 * @var int
	 */
	private $statusCode = self::_STATUS_CODE_OK;
	
	/**
	 * @var string
	 */
	private $contentType = self::_CONTENT_TYPE_HTML;
	
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
			throw new Exception('Unknown HTTP status code');
		}
		
		$this->statusCode = $code;
		
		return $this;
	}
	
	/**
	 * @param string $key
	 * @param string $value
	 * @return Response
	 */
	public function addHeader($key, $value){
		$this->headers[$key] = $value;
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
	 * @return Response
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
	 * Output response (headers and body)
	 * 
	 * @param string $body
	 */
	public function send($body = '') {
		$this
			->appendCookies()
			->appendHeaders()
		;
		
		echo $body;
	}
	
	/**
	 * @return Response
	 */
	private function appendHeaders() {
		
		// Send status code header
		http_response_code($this->statusCode);
		
		// Send conetnt type header
		$this
			->addHeader('Content-Type', $this->contentType)
		;

		// Set application headers
		foreach ($this->headers as $key => $value){
			header($key . ': ' . $value);
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