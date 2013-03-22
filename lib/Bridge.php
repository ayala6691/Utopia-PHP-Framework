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

trait Bridge {

	/**
	 * @return Application
	 */
	protected function getApp(){
		return Application::getInstance();
	}
	
	/**
	 * @return Layout
	 */
	protected function getLayout() {
		return $this->getApp()->getLayout();
	}
	
	/**
	 * @return Request
	 */
	protected function getRequest() {
		return $this->getApp()->getRequest();
	}
	
	/**
	 * @return Response
	 */
	protected function getResponse() {
		return $this->getApp()->getResponse();
	}
	
	/**
	 * @return Router
	 */
	protected function getRouter() {
		return $this->getApp()->getRouter();
	}
	
	/**
	 * @return bool
	 */
	protected function isDev() {
		return $this->getApp()->isDev();
	}
}