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

if(isset($configurator->container->params['envOptions']['editor'])){
	Debugger::$editor = $configurator->container->params['envOptions']['editor'];
}

// Configure application
$application = $configurator->container->application;
$application->errorPresenter = 'Error';
$application->catchExceptions = false;

// Setup router
$application->onStartup[] = function() use ($application) {
	$router = $application->getRouter();
	$router[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
	$router[] = new Route('admin/<presenter david>/[<action>[/<id [0-9]+>]]', array(
		'module' => 'Admin',
		'presenter' => NULL,
		'action' =>  'list'
	));
	$router[] = new AdminRoute('admin/<form>/[<action list|edit|add>[/<id [0-9]+>]]', array(
		'module' => 'Admin',
		'presenter' => 'Admin',
		'action' =>  'list'
	));
};

// Run the application!
$application->run();
