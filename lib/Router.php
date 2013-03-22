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

abstract class Router {
	use Bridge;
	
	public function init() {}

	/**
	 * @return string
	 */
	abstract public function getController();
	
	/**
	 * @return string
	 */
	abstract public function getAction();

	/**
	 * @param string $action
	 * @param string $controller
	 * @param array $vars
	 * @return string
	 */
	abstract public function getUrl($action, $controller, array $vars = array());
	
	/**
	 * @return array
	 */
	public function getVars() {}
}