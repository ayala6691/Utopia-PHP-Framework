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

use PDO;
use Exception;

class Database {
	
	/**
	 * @var PDO
	 */
	public static $db = array();
	
	/**
	 * @var int
	 */
	static private $connections = 0;
	
	private function __construct() {}
	
	private function __clone() {}
	
	/**
	 * @param string $host
	 * @param string $name
	 * @param string $uname
	 * @param string $password
	 * @throws Exception
	 * @return \Utopia\PDO
	 */
	static public function connect($host, $name, $uname, $password) {
		$key = md5($host . '-' . $name . '-' . $uname . '-' . $password);

		try {
			self::$db[$key] = new PDO('mysql:host=' . $host . ';dbname=' . $name, $uname, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			++self::$connections;
		}
		catch (Exception $e) {
			throw $e;
		}
		
		return self::$db[$key];
	}
}