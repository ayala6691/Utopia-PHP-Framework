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

/**
 * Application Lifecycle:
 * 
 *	1. Router Initialization
 * 	2. Dispatch routed xAction
 * 		- Create View
 * 		- Create Controller
 * 		- Attach View to the Controller
 * 		- Process action
 * 		- Render View
 * 		- Attach output to the Layout
 * 	3. Send response
 */
class Application {
	
	/**
	 * @var Application
	 */
	private static $instance = null;

	/**
	 * @var Layout
	 */
	private $layout = null;
	
	/**
	 * @var Loader
	 */
	private $loader = null;
	
	/**
	 * @var Request
	 */
	private $request = null;
	
	/**
	 * @var response
	 */
	private $response = null;
	
	/**
	 * @var Router
	 */
	private $router = null;

	/**
	 * @var bool
	 */
	private $dev = false;
	
	/**
	 * @var array
	 */
	private $registry =	array();
	
	/**
	 * @var array
	 */
	private $methods = array();
	
	/**
	 * @return Application
	 */
	private function __construct() {
		
		// Initialize autoloader
		$this->loader = new Loader();
		
		// Set up framework dependency
		$this
			->addDependency('Utopia', __DIR__);
		
		/**
		 * Set Core Models
		 */
		$this
			->setLayout(new Layout())
			->setRequest(new Request())
			->setResponse(new Response())
		;
	}
	
	private function __clone() {}
	
	/**
	 * @return Application
	 */
	public static function getInstance() {
		
		if (null === self::$instance){
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	/**
	 * Run's application lifecycle
	 *
	 * 	1. Router Initialization
	 * 	2. Dispatch routed xAction
	 * 	3. Send response
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
	 * @param Layout $layout
	 */
	public function setLayout(Layout $layout) {
		$this->layout = $layout;
		return $this;
	}
	
	/**
	 * @param Request $request
	 */
	public function setRequest(Request $request) {
		$this->request = $request;
		return $this;
	}
	
	/**
	 * @param Response $response
	 */
	public function setResponse(Response $response) {
		$this->response = $response;
		return $this;
	}
	
	/**
	 * @param Router $router
	 */
	public function setRouter(Router $router) {		
		$this->router = $router; 
		return $this;
	}
	
	/**
	 * @return Layout
	 */
	public function getLayout() {
		return $this->layout;
	}
	
	/**
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * @return Response
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * @return Router
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * @return Application
	 */
	public function setDev($status = true) {
		$this->dev = $status;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDev() {
		return $this->dev;
	}
	
	/**
	 * @param string $key
	 * @param string $path
	 * @return Application
	 */
	public function addDependency($namespace, $path) {
		$this->loader->addDependency($namespace, $path);
		return $this;
	}

	/**
	 * This is where MVC comes all together
	 *
	 * 	- Create View
	 * 	- Create Controller
	 * 	- Attach View to the Controller
	 * 	- Process action
	 * 	- Render View
	 * 	- Attach output to the Layout
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
	
		$controller = new $controller();
	
		// Attach view to controller
		$controller->setView($view);
	
		// Process action
		$action = $action . 'Action';
	
		try {
			$controller->init();
	
			if (method_exists($controller, $action)) {
				$controller->$action();
			}
	
			else {
				throw new Exception('Unknown Action "' . $action . '"');
			}
	
			$controller->shutdown();
		}
		catch (Exception $e) { // Call error action instead
			$controller->errorAction($e);
		}
	
		if (!$this->getLayout()->isRendered()) {
			$this->getLayout()->setBody($view->render());
		}
	}
}























































/*_____ad88888888888888888888888a, 
________a88888"8888888888888888888888, 
______,8888"__"P88888888888888888888b, 
______d88_________`""P88888888888888888, 
_____,8888b_______________""88888888888888, 
_____d8P'''__,aa,______________""888888888b 
_____888bbdd888888ba,__,I_________"88888888, 
_____8888888888888888ba8"_________,88888888b 
____,888888888888888888b,________,8888888888 
____(88888888888888888888,______,88888888888, 
____d888888888888888888888,____,8___"8888888b 
____88888888888888888888888__.;8'"""__(888888 
____8888888888888I"8888888P_,8"_,aaa,__888888 
____888888888888I:8888888"_,8"__`b8d'__(88888 
____(8888888888I'888888P'_,8)__________88888 
_____88888888I"__8888P'__,8")__________88888 
_____8888888I'___888"___,8"_(._.)_______88888 
_____(8888I"_____"88,__,8"_____________,8888P 
______888I'_______"P8_,8"_____________,88888) 
_____(88I'__________",8"__M""""""M___,888888' 
____,8I"____________,8(____"aaaa"___,8888888 
___,8I'____________,888a___________,8888888) 
__,8I'____________,888888,_______,888888888 
_,8I'____________,8888888'`-===-'888888888' 
,8I'____________,8888888"________88888888" 
8I'____________,8"____88_________"888888P 
8I____________,8'_____88__________`P888" 
8I___________,8I______88____________"8ba,. 
(8,_________,8P'______88______________88""8bma,. 
_8I________,8P'_______88,______________"8b___""P8ma, 
_(8,______,8d"________`88,_______________"8b_____`"8a 
__8I_____,8dP_________,8X8,________________"8b.____:8b 
__(8____,8dP'__,I____,8xxx8,________________`88,____8) 
___8,___8dP'__,I____,8XxxxX8,_____I,_________8X8,__,8 
___8I___8P'__,I____,8XxxxxxX8,_____I,________`8X88,I8 
___I8,__"___,I____,8XxxxxxxxX8b,____I,________8xxx88I, 
___`8I______I'__,8XxxxxxxxxxxxXX8____I________8XXxxXX8, 
____8I_____(8__,8XxxxxxxxxxxxxxxX8___I________8XxxxxxXX8, 
___,8I_____I[_,8XxxxxxxxxxxxxxxxxX8__8________8XxxxxxxxX8, 
___d8I,____I[_8XxxxxxxxxxxxxxxxxxX8b_8_______(8XxxxxxxxxX8, 
___888I____`8,8XxxxxxxxxxxxxxxxxxxX8_8,_____,8XxxxxxxxxxxX8 
___8888,____"88XxxxxxxxxxxxxxxxxxxX8)8I____.8XxxxxxxxxxxxX8 
__,8888I_____88XxxxxxxxxxxxxxxxxxxX8_`8,__,8XxxxxxxxxxxxX8" 
__d88888_____`8XXxxxxxxxxxxxxxxxxX8'__`8,,8XxxxxxxxxxxxX8" 
__888888I_____`8XXxxxxxxxxxxxxxxX8'____"88XxxxxxxxxxxxX8" 
__88888888bbaaaa88XXxxxxxxxxxxXX8)______)8XXxxxxxxxxXX8" 
__8888888I,_``""""""8888888888888888aaaaa8888XxxxxXX8" 
__(8888888I,______________________.__```"""""88888P" 
___88888888I,___________________,8I___8,_______I8" 
____"""88888I,________________,8I'____"I8,____;8" 
___________`8I,_____________,8I'_______`I8,___8) 
____________`8I,___________,8I'__________I8__:8' 
_____________`8I,_________,8I'___________I8__:8 
______________`8I_______,8I'_____________`8__(8 
_______________8I_____,8I'________________8__(8; 
_______________8I____,8"__________________I___88, 
______________.8I___,8'_____Utopia Framework__8"8, 
______________(PI___'8_______________________,8,`8, 
_____________.88'____________,@@___________.a8X8,`8, 
_____________(88_____________@@@_________,a8XX888,`8, 
____________(888_____________@@'_______,d8XX8"__"b_`8, 
___________.8888,_____________________a8xxx8"____"a_`8, 
__________.888X88___________________,d8XX8I"______9,_`8, 
_________.88:8XX8,_________________a8XxX8I'_______`8__`8, 
________.88'_8XxX8a_____________,ad8XxX8I'________,8___`8, 
________d8'__8XxxxX8ba,______,ad8XxxX8I"__________8__,__`8, 
_______(8I___8XxxxxxX888888888XxxxX8I"____________8__II__`8 
_______8I'___"8XxxxxxxxxxxxxxxxxxX8I'____________(8__8)___8; 
______(8I_____8XxxxxxxxxxxxxxxxxX8"______________(8__8)___8I 
______8P'_____(8XxxxxxxxxxxxxxX8I'________________8,_(8___:8 
_____(8'_______8XxxxxxxxxxxxxxX8'_________________`8,_8____8 
_____8I________`8XxxxxxxxxxxxX8'___________________`8,8___;8 
_____8'_________`8XxxxxxxxxxX8'_____________________`8I__,8' 
_____8___________`8XxxxxxxxX8'_______________________8'_,8' 
_____8____________`8XxxxxxX8'________________________8_,8' 
_____8_____________`8XxxxX8'________________________d'_8' 
_____8______________`8XxxX8_________________________8_8' 
_____8________________"8X8'_________________________"8" 
_____8,________________`88___________________________8 
_____8I________________,8'__________________________d) 
_____`8,_______________d8__________________________,8 
______(b_______________8'_________________________,8' 
_______8,_____________dP_________________________,8' 
_______(b_____________8'________________________,8' 
________8,___________d8________________________,8' 
________(b___________8'_______________________,8' 
_________8,_________a8_______________________,8' 
_________(b_________8'______________________,8' 
__________8,_______,8______________________,8' 
__________(b_______8'_____________________,8' 
___________8,_____,8_____________________,8' 
___________(b_____8'____________________,8' 
____________8,___d8____________________,8' 
____________(b__,8'___________________,8' 
_____________8,,I8___________________,8' 
_____________I8I8'__________________,8' 
_____________`I8I__________________,8' 
______________I8'_________________,8' 
______________"8_________________,8' 
______________(8________________,8' 
______________8I_______________,8' 
______________(b,___8,________,8) 
______________`8I___"88______,8i8, 
_______________(b,__________,8"8") 
_______________`8I__,8______8)_8_8 
________________8I__8I______"__8_8 
________________(b__8I_________8_8 
________________`8__(8,________b_8, 
_________________8___8)________"b"8, 
_________________8___8(_________"b"8 
_________________8___"I__________"b8, 
_________________8________________`8) 
_________________8_________________I8 
_________________8_________________(8 
_________________8,_________________8, 
_________________Ib_________________8) 
_________________(8_________________I8 
__________________8_________________I8 
__________________8_________________I8 
__________________8,________________I8 
__________________Ib________________8I 
__________________(8_______________(8' 
___________________8_______________I8 
___________________8,______________8I 
___________________Ib_____________(8' 
___________________(8_____________I8 
___________________`8_____________8I 
____________________8____________(8' 
____________________8,___________I8 
____________________Ib___________8I 
____________________(8___________8' 
_____________________8,_________(8 
_____________________Ib_________I8 
_____________________(8_________8I 
______________________8,________8' 
______________________(b_______(8 
_______________________8,______I8 
_______________________I8______I8 
_______________________(8______I8 
________________________8______I8, 
________________________8______8_8, 
________________________8,_____8_8' 
_______________________,I8_____"8" 
______________________,8"8,_____8, 
_____________________,8'_`8_____`b 
____________________,8'___8______8, 
___________________,8'____(a_____`b 
__________________,8'_____`8______8, 
__________________I8/______8______`b, 
__________________I8-/_____8_______`8, 
__________________(8/-/____8________`8, 
___________________8I/-/__,8_________`8 
___________________`8I/--,I8________-8) 
____________________`8I,,d8I_______-8) 
______________________"bdI"8,_____-I8 
___________________________`8,___-I8' 
____________________________`8,,--I8 
_____________________________`Ib,,I8 
______________________________`I8I*/