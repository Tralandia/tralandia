<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route;

// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
require_once APP_DIR . '/Configurator.php';
require_once LIBS_DIR . '/tools.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable();

// Load configuration from config.neon file);
$configurator = new Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(APP_DIR . '/config.neon', isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null);
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();
$configurator->setTempDirectory(TEMP_DIR);
$container = $configurator->createContainer();

//if(isset($container->parameters['editor'])){
//	Debugger::$editor = $container->parameters['editor'];
//}

// Enable dynamic presenter factory
DynamicPresenterFactory::enable($robotLoader);

// Configure application
$application = $container->application;
$application->errorPresenter = 'Error';
$application->catchExceptions = false;

// Setup router
$application->onStartup[] = function() use ($application, $container) {
	$router = $application->getRouter();
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
	$router[] = new Route('admin/<presenter>/[<action list|add|registration>]', array(
		'module' => 'Admin',
		'presenter' => 'Admin',
		'action' =>  'list'
	));
};


// Run the application!
$application->run();
