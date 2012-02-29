<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route;

// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';
require LIBS_DIR . '/tools.php';

// Enable Nette\Debug for error visualisation & logging
//Debugger::$strictMode = TRUE;
Debugger::enable();

// Load configuration from config.neon file);
require_once APP_DIR . '/Configurator.php';
$configurator = new Configurator;
$configurator->loadConfig(APP_DIR . '/config.neon', isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null);
//$configurator->container->robotLoader->autoRebuild = true;


if(isset($configurator->container->params['editor'])){
	Debugger::$editor = $configurator->container->params['editor'];
}

// Enable dynamic presenter factory
DynamicPresenterFactory::enable();

// Configure application
$application = $configurator->container->application;
$application->errorPresenter = 'Error';
$application->catchExceptions = false;

// Setup router
$application->onStartup[] = function() use ($application) {
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
