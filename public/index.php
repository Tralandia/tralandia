<?php
//ini_set('display_errors', '1'); error_reporting(E_ALL);

// if (PHP_SAPI != 'cli') {

// 	if(array_key_exists('tester', $_GET)) {
// 		setcookie("tester", (int)$_GET['tester']);
// 		header("Location: /");
// 		die();
// 	}

// 	if(!array_key_exists('tester', $_COOKIE) || !$_COOKIE['tester']) {
// 		require __DIR__ . '/landingPage/index.html';
// 		exit;
// 	}
// }

// the identification of this site
define('SITE', '');
define('CENTRAL_LANGUAGE', 38);

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the root directory
define('ROOT_DIR', WWW_DIR . '/..');

// absolute filesystem path to the files
define('FILES_DIR', WWW_DIR . '/storage');

// absolute filesystem path to the application root
define('APP_DIR', ROOT_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', ROOT_DIR . '/libs');

define('VENDOR_DIR', ROOT_DIR . '/vendor');


// absolute filesystem path to the libraries
define('TEMP_DIR', ROOT_DIR . '/temp');

require APP_DIR . '/entityConstants.php';

// load bootstrap file
require APP_DIR . '/bootstrap.php';
