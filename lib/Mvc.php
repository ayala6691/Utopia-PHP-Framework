<?php
/**
 * Utopia PHP Framework
 *
 * @package Framework
 * @subpackage Core
 * 
 * @link http://code.google.com/p/utopia-php-framework/
 * @author Eldad Fux <eldad@fuxie.co.il>
 * @version 1.0 RC2
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia;

class Mvc extends Plugin {

	const _DEFAULT_ZONE = 'content';
	
	/**
	 * @var array
	 */
	private $html = array();

	/**
	 * @var array
	 */
	private	$controllers = array();

	/**
	 * @var bool
	 */
	private $init = false;

	/**
	 * @param string | null $zone
	 * @param string $controller
	 * @param string $action
	 * @param mixed $vars
	 */
	public function getAction($zone = null, $controller = 'default', $action = 'index', $vars = null){
		return $this->dispatcher($zone, $controller, $action, $vars);
	}

	/**
	 * @return string
	 */
	public function run() {
		
		$this->getApp()->getRouter()->init();

		if (!$this->init){ /* Get default controller initAction */
			$this->init = true;
			$this->getAction(self::_DEFAULT_ZONE, $this->getApp()->getRouter()->getController(), 'init');
		}
		
		/* Get default action */
		$this->getAction(self::_DEFAULT_ZONE, $this->getApp()->getRouter()->getController(), $this->getApp()->getRouter()->getAction(), $this->getApp()->getRouter()->getVars());

		/* Parse layout template */
		if (!$this->getLayout()->isRendered()) {
			foreach ($this->html as $key => $value) {
				$this->getLayout()->setParam($key, $value);
			}
		}
		
		/* Set Response */
		$this->getResponse()->send();
		
		/* Print page */
		return $this->getLayout()->render();
	}
	
	/**
	 * This is where MVC comes together
	 * 
	 * 	- Create view
	 * 	- Create controller
	 * 	- Attach view to the controller
	 * 	- Process action
	 * 	- Render view
	 * 
	 * @param string 	$zone	Action zone tag in the application layout, apply null if no related tag needed
	 * @param mixed 	$cname	Controller name
	 * @param mixed 	$data	Action name
	 * @param mixed 	$vars	multiple parameters as an object or array (optional)
	 * @return string
	 */
	private function dispatcher($zone, $cname, $aname, $vars = null){
		
		if ((!isset($this->html[$zone])) && ($zone != null)) { /* Set key value if !isset */
			$this->html[$zone] = '';
		}

		// Create View
		$view = new View();
		$view->setPath('../app/view/' . strtolower($cname . '/' . $aname) . '.phtml')->setParam('vars', $vars);
		
		// Create controller
		$cname = $cname.'Controller';

		if (!array_key_exists($cname, $this->controllers)){
			$path = '../app/controllers/'.$cname.'.php';
			
			if (is_readable($path)) {
				require_once $path;	
			}
			else {
				throw new \Exception('No ' . $name . ' controller exists');
			}
			
			$this->controllers[$cname] = new $cname();
		}
		
		// Attach view to controller
		$this->controllers[$cname]->setView($view);

		// Process action
		$action = $aname . 'Action';
		
		try {
			$this->controllers[$cname]->$action();
		}
		catch (\Exception $e) { /* call error action instead */
			$this->controllers[$cname]->errorAction($e);
		}
		
		// Render view
		$html = $view->render();
		
		if (null != $zone) {
			$this->html[$zone] .= $html;
		}

		return $html;
	}
}