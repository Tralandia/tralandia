<?php

use Nette\Diagnostics\Debugger,
	Nella\Addons\Doctrine\Config\Extension;

// Absolute filesystem path to the web root
define('ROOT_DIR', __DIR__ . '/..');
define('TEMP_DIR', ROOT_DIR . '/temp');
define('LIBS_DIR', ROOT_DIR . '/libs');
define('APP_DIR', ROOT_DIR . '/app');
define('TESTS_DIR', ROOT_DIR . '/tests');
define('INCLUDE_DIR', TESTS_DIR . '/include');

$_SERVER['HTTP_HOST'] = 'localhost';

// Load Nette Framework
require LIBS_DIR . '/Nette/nette.min.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable(FALSE);
//Debugger::$strictMode = FALSE;
// $section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null;
$section = 'test';
// Load configuration from config.neon
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableDebugger(ROOT_DIR . '/log');
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

require_once LIBS_DIR . '/tools.php';
Extension::register($configurator);
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$configurator->onCompile[] = callback('Extras\PresenterGenerator', 'generate');
$container = $configurator->createContainer();

ob_start();