<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
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
$container->createList();

// Pridanie sluzby robot loadera
$container->addService('robotLoader', $robotLoader); // dolezite pre dynamicke presentery
Debugger::$editor = $container->parameters['editor'];
//Debugger::$strictMode = FALSE;


// Setup router // TODO: presunut do config.neon
$container->application->onStartup[] = function() use ($container) {
	$router = $container->application->getRouter();

	$router[] = $adminRouter = new RouteList('Admin');
	$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
	$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
		'presenter' => NULL,
		'action' =>  'list'
	));
	$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
		'presenter' => 'Rental',
		'action' =>  'edit'
	));
/*	$adminRouter[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
		'presenter' => 'Admin',
		'action' =>  'list'
	));
*/	$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
		'presenter' => 'Admin',
		'action' =>  'list'
	));

	$router[] = $frontRouter = new RouteList('Front');
	$frontRouter[] = new Route('<presenter>/[<action>[/<id>]]', 'Home:default');

};

// Run the application!
if (PHP_SAPI == 'cli') {
	$container->console->run();
} else {
	$container->application->run();
}
