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

class Config {
	
	/**
	 * @var array
	 */
	private $config = array();
	
	/**
	 * @param string $path
	 */
	public function __construct($path) {
		$this->config = include $path;
	}
	
	/**
	 * Returns config value by path.
	 * Example string: '/general/websites/1/name'
	 * 
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function getConfig($path, $default = null) {
		$path = explode('/', $path);
		$temp = $this->config;
		
		foreach ($path as $key){
			$temp = (isset($temp[$key])) ? $temp[$key] : null;
			
			if (null !== $temp){
				$value = $temp;
			}else{
				return $default;
			}
		}
		
		return $value;
	}
}