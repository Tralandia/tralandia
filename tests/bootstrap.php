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
require_once LIBS_DIR . '/Nette/loader.php';

// Load configuration from config.neon
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters(array('appDir' => APP_DIR));
$configurator->enableDebugger(ROOT_DIR . '/log');
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(__DIR__)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

require_once LIBS_DIR . '/tools.php';
Extension::register($configurator);
Extras\Config\PresenterExtension::register($configurator);

$configurator->addConfig(APP_DIR . '/configs/config.neon', FALSE);
$configurator->addConfig(APP_DIR . '/configs/test.config.neon');
$configurator->onCompile[] = function ($configurator, $compiler) {
	Extras\PresenterGenerator::generate();
	$compiler->addExtension('gpspicker', new VojtechDobes\NetteForms\GpsPickerExtension);
};
$container = $configurator->createContainer();

Debugger::enable(FALSE);


//ob_start();