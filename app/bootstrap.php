<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nella\Addons\Doctrine\Config\Extension;

// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
require_once LIBS_DIR . '/tools.php';
require_once LIBS_DIR . '/rado_functions.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable();

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableDebugger(ROOT_DIR . '/log');

// Enable RobotLoader - this will load all classes automatically
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Setup doctrine loader
Extension::register($configurator);

// Create Dependency Injection container from config.neon file
$configurator->addConfig(APP_DIR . '/config.neon', isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null);
$container = $configurator->createContainer();
$container->createService();

// Pridanie sluzby robot loadera
$container->addService('robotLoader', $robotLoader); // dolezite pre dynamicke presentery
Debugger::$editor = $container->parameters['editor'];


// Setup router // TODO: presunut do config.neon
$container->application->onStartup[] = function() use ($container) {
	$router = $container->application->getRouter();
	$router[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
	$router[] = new Route('admin/<presenter david>/[<action>[/<id>]]', array(
		'module' => 'Admin',
		'presenter' => NULL,
		'action' =>  'list'
	));
	$router[] = new Route('admin/<presenter>/<id [0-9]+>', array(
		'module' => 'Admin',
		'presenter' => 'Rental',
		'action' =>  'edit'
	));
/*	$router[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
		'module' => 'Admin',
		'presenter' => 'Admin',
		'action' =>  'list'
	));
*/	$router[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
		'module' => 'Admin',
		'presenter' => 'Admin',
		'action' =>  'list'
	));

};

// Run the application!
if (PHP_SAPI == 'cli') {
	$container->console->run();
} else {
	$container->application->run();
}
