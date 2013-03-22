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

class Database {
	
	/**
	 * @var PDO
	 */
	public static $db = array();
	
	/**
	 * @var int
	 */
	private static $connections = 0;
	
	private function __construct() {}
	
	private function __clone() {}
	
	/**
	 * Gets array with database login details and set a PDO object at Database::$db
	 * Currently support one connection per application. 
	 * 
	 * @param array $db
	 * @throws \Exception
	 * @return \PDO
	 */
	public static function connect(array $db) {
		$key = md5(implode('-', $db));

		try {
			self::$db[$key] = new \PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['name'], $db['uname'], $db['pass'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			++self::$connections;
		}
		catch (\PDOException $e) {
			throw new \Exception($e->getMessage());
		}
		
		return self::$db[$key];
	}
}