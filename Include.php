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

/**
 * Version Check
 * 
 * PHP 5.4.0 features in use:
 * 	- Traits
 * 
 * PHP 5.4.0 functions in use:
 * 	- http_response_code
 * 
 */
$phpVersion = '5.4.0';

if (0 > version_compare(PHP_VERSION, $phpVersion)) { // Check PHP version number
	throw new Exception("Utopia Framework was designed and tested on PHP version ' . $phpVersion . ' or later, please update your version\n");
}

require __DIR__ . '/lib/Application.php';
require __DIR__ . '/lib/Loader.php';