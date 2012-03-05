<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nella\Addons\Doctrine\Config\Extension;

// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
//require_once APP_DIR . '/Configurator.php';
require_once LIBS_DIR . '/tools.php';

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

//if(isset($container->parameters['editor'])){
//	Debugger::$editor = $container->parameters['editor'];
//}

//debug($container->doctrine);
/*
// Enable dynamic presenter factory
DynamicPresenterFactory::enable($robotLoader);

// Register the ORM Annotations in the AnnotationRegistry
Doctrine\Common\Annotations\AnnotationRegistry::registerFile(LIBS_DIR . '/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$reader = new Doctrine\Common\Annotations\SimpleAnnotationReader();
$reader->addNamespace('Doctrine\ORM\Mapping');
$reader = new Doctrine\Common\Annotations\CachedReader($reader, new Doctrine\Common\Cache\ArrayCache());

$driver = new Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, (array)$paths);

$config->setMetadataDriverImpl($driver);
*/

// Configure application
$application = $container->application;
//$application->errorPresenter = 'Error';
//$application->catchExceptions = false;

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
if (PHP_SAPI == 'cli') {
	$container->console->run();
} else {
	$container->application->run();
}
