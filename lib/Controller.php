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

abstract class Controller {
	use Bridge;
	
	/**
	 * @var View
	 */
	protected $view = null;
	
	public function initAction() {
		$this->getView()->setRendered();
	}
	
	/**
	 * @param \Exception $e
	 * @throws \Exception
	 */
	public function errorAction(Exception $e) {
		throw $e;
	}
	
	/**
	 * @param View $view
	 * @return Controller
	 */
	public function setView(View $view) {
		$this->view = $view;
		return $this;
	}
	
	/**
	 * @return View
	 */
	protected  function getView() {
		return $this->view;
	}
	
	/* Controller Helpers -> this will be implemented as external files in feature version  */
	
	/**
	 * @param string $zone
	 * @param string $controller
	 * @param string $action
	 * @param mixed $vars
	 * @throws \Exception
	 * @return bool
	 */
	protected  function getAction($zone, $controller, $action, $vars = null) {
		if (null == $zone) {
			//throw new \Exception('Can\'t echo action directly from controller');
		}
		
		return $this->getApp()->getMvc()->getAction($zone, $controller, $action, $vars);
	}
	
	/**
	 * @param array $data
	 */
	protected function json(array $data) {
		$this->getResponse()->setContentType(Response::_CONTENT_TYPE_JSON); /* Set Content Type */
		$this->getLayout()
			->setRendered()
			->setParam(Mvc::_DEFAULT_ZONE, json_encode($data)); /* Append json string to output tag */
	}

	/**
	 * @param string $callback
	 * @param array $data
	 */
	protected function jsonp($callback, array $data) {
		$this->getResponse()->setContentType(Response::_CONTENT_TYPE_JAVASCRIPT); /* Set Content Type */
		$this->getLayout()
			->setRendered()
			->setParam(Mvc::_DEFAULT_ZONE, 'parent.' . $callback . '(' . json_encode($data) . ');'); /* Append json string to output tag */
	}
	
	/**
	 * @param string $callback
	 * @param array $data
	 */
	protected function iframe($callback, array $data) {
		$this->getLayout()
			->setRendered()
			->setParam(Mvc::_DEFAULT_ZONE, '<script type="text/javascript">window.parent.' . $callback . '(' . json_encode($data) . ');</script>'); /* Append json string to output tag */
	}

	/**
	 * @param object $data
	 * @return mixed
	 */
	protected function xml(array $data) {
		$xml	= new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root />');
		$this->arr2xml($data, $xml);
		
		$this->getResponse()->setContentType(Response::_CONTENT_TYPE_XML); /* Set Content Type */
		$this->getLayout()
		->setRendered()
			->setParam(Mvc::_DEFAULT_ZONE, $xml->asXML()); /* Append XML string to output tag */
	}

	/**
	 * @param array $array
	 * @param SimpleXMLElement $xml
	 */
	protected function arr2xml(array $array, \SimpleXMLElement $xml) {

		foreach ($array as $key => $value) {
			$attr = null;

			if (is_numeric($key)) {
				$attr	= $key;
				$key	= 'xitem';
			}

			if (is_array($value)) { // Handle Arrays
				$child	= $xml->addChild($key);
				$this->arr2xml($value, $child);
			}
			elseif ($value instanceof \ArrayObject) { // Handle ArrayObject's
				$child	= $xml->addChild($key);
				$this->arr2xml($value->getArrayCopy(), $child);
			}
			else {
				// TODO: temporary hack to handle special html entities (&raquo; for example) - XML does not allow unrecognize "&";
				$value	= str_replace('&amp;',			'@@@@AMP@@@@',	$value);
				$value	= str_replace('&',				'&amp;',		$value);
				$value	= str_replace('@@@@AMP@@@@',	'&amp;',		$value);
				
				$child	= $xml->addChild($key, $value);
			}

			if (null !== $attr) {
				$child->addAttribute('key', $attr);
			}
		}
	}
}