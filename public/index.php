<?php

// the identification of this site
define('SITE', '');

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

// absolute filesystem path to the libraries
define('TEMP_DIR', ROOT_DIR . '/temp');

// load bootstrap file
require APP_DIR . '/bootstrap.php';