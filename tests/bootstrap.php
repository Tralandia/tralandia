<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route;

// Absolute filesystem path to the web root
define('ROOT_DIR', __DIR__ . '/..');
define('TEMP_DIR', ROOT_DIR . '/temp');
define('LIBS_DIR', ROOT_DIR . '/libs');
define('APP_DIR', ROOT_DIR . '/app');
define('TESTS_DIR', ROOT_DIR . '/tests');
$_SERVER['HTTP_HOST'] = 'localhost';

// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';

// Enable Nette\Debug for error visualisation & logging
$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null;
Debugger::enable(false);
Debugger::$strictMode = false;

// Load configuration from config.neon
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();
$container = $configurator->createContainer();
require LIBS_DIR . '/tools.php';

//debug($container);
ob_start();