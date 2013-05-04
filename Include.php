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

// Version check
if (0 > version_compare(PHP_VERSION, '5.4.0')) { // Check PHP version number
	throw new Exception("Utopia Framework was designed and tested on PHP version 5.4.0 or later, please update your version\n");
}

require __DIR__ . '/lib/Application.php';
require __DIR__ . '/lib/Loader.php';