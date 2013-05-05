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

/**
 * Utopia Loader implements the technical interoperability
 * standards for PHP 5.4 namespaces and class names as defined by the PHP Framework Interoperability Group (PHP-FIG) autoloading standard [PSR-0].
 * 
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 */
class Loader {
		
	/**
	 * @var array
	 */
	private $dependencies = array();
	
	public function __construct() {
		spl_autoload_register(array($this, 'loader'));		
	}

	/**
	 * @param string $className
	 * @throws Exception
	 */
	public function loader($className) {
		$namespace = substr($className, 0, strpos($className, '\\')); // Get the enviorment key so we could assign it to project path
		
		$search		= array($namespace . '\\', '\\');
		$replace	= array('', DIRECTORY_SEPARATOR);
		$className	= str_replace($search, $replace, $className);
		
		if (!array_key_exists($namespace, $this->dependencies)) {
			throw new Exception('"' . $namespace . '" enviorment doesn\'t exists or is not registered');
		}
		
		require_once $this->dependencies[$namespace] . DIRECTORY_SEPARATOR . $className . '.php';
	}
	
	/**
	 * @param string $path
	 * @param integer $weight
	 * @return Loader
	 */
	public function addDependency($namespace, $path) {
		$this->dependencies[$namespace] = $path;
		return $this;
	}
}