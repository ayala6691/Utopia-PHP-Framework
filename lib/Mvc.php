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

class Mvc {
	
	use Bridge;
	
	/**
	 * @var Controller
	 */
	protected $controller = null;
	
	/**
	 * Run's application lifecycle
	 * 
	 * 	1. Router Initialization
	 * 	2. Dispatch initAction
	 * 	3. Dispatch routed xAction
	 * 	4. Dispatch shutdownAction
	 * 	5. Send response
	 */
	public function run() {
		// Initialize router
		$this->getRouter()->init();
		
		// Excute routed xAction
		$body = $this->dispatcher($this->getRouter()->getController(), $this->getRouter()->getAction(), $this->getRouter()->getVars());

		// Send Response
		$this->getResponse()->send($this->getLayout()->render());
	}
	
	/**
	 * This is where MVC comes together
	 *
	 * 	- Create View
	 * 	- Create Controller
	 * 	- Attach View to the Controller
	 * 	- Process action
	 * 	- Render View
	 * 
	 * @param string $controller
	 * @param string $action
	 * @param mixed $vars
	 * @throws Exception
	 * @return string
	 */
	private function dispatcher($controller, $action, $vars = null){
		
		// Set view template path
		$view = new View();
		
		$view
			->setPath('../app/views/' . strtolower($controller . '/' . $action) . '.phtml')
			->setParam('vars', $vars);
	
		// Create controller
		$controller = ucfirst($controller) . 'Controller';

		$path = '../app/controllers/' . $controller . '.php';
			
		if (is_readable($path)) {
			require_once $path;
		}
		else {
			throw new Exception($controller . ' controller doesn\'t exists');
		}

		$this->controller = new $controller();

		// Attach view to controller
		$this->controller->setView($view);
	
		// Process action
		$action = $action . 'Action';
	
		try {
			$this->controller->init();
			
			if (method_exists($this->controller, $action)) {
				$this->controller->$action();
			}
			
			else {
				throw new Exception('Unknown Action "' . $action . '"');
			}
			
			$this->controller->shutdown();
		}
		catch (Exception $e) { // Call error action instead
			$this->controller->errorAction($e);
		}
		
		if (!$this->getLayout()->isRendered()) {
			$this->getLayout()->setBody($view->render());
		}
	}
}