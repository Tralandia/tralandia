<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nella\Addons\Doctrine\Config\Extension;


// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
require_once LIBS_DIR . '/rado_functions.php';

$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null;
// Enable Nette\Debug for error visualisation & logging
Debugger::enable();
//Debugger::$strictMode = FALSE;


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableDebugger(ROOT_DIR . '/log');



// Enable RobotLoader - this will load all classes automatically
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

//var_dump($robotLoader);
require_once LIBS_DIR . '/tools.php';
// Create Dependency Injection container from config.neon file
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$configurator->onCompile[] = callback('Extras\PresenterGenerator', 'generate');
$container = $configurator->createContainer();

// Pridanie sluzby robot loadera
# $container->addService('robotLoader', $robotLoader); // dolezite pre dynamicke presentery
Debugger::$editor = $container->parameters['editor'];

$serviceConfigurator = new Extras\Configurator;
$serviceConfigurator->setTempDirectory(TEMP_DIR);

Extension::register($serviceConfigurator);

$serviceConfigurator->addConfig(APP_DIR . '/configs/service.neon', $section);
$serivceContainer = $serviceConfigurator->createContainer();

// Setup doctrine loader
$serivceContainer->createService();
$serivceContainer->createList();
// @todo toto niekam schovat
require_once APP_DIR . '/extras/EntityAnnotation.php';
require_once APP_DIR . '/extras/UIAnnotation.php';
Extras\Models\Service::$translator = $container->translator;

// Setup router // TODO: presunut do config.neon
$container->application->onStartup[] = function() use ($container) {
	$router = $container->application->getRouter();

	$router[] = $adminRouter = new RouteList('Admin');
	$adminRouter[] = new Route('index.php', 'Admin:Rental:list', Route::ONE_WAY);
	$adminRouter[] = new Route('admin/<presenter>/<id [0-9]+>', array(
		'presenter' => NULL,
		'action' =>  'edit'
	));
	$adminRouter[] = new Route('admin/<presenter>/[<action>[/<id>]]', array(
		'presenter' => NULL,
		'action' =>  'list'
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
/*	$frontRouter[] = new \Extras\Route($container->routerCache, array(
								'presenter' => 'David',
								'action' => 'default',
								'country' => 'SK',
							));
*/	$frontRouter[] = new Route('<presenter>/[<action>[/<id>]]', 'Home:default');

};

// Run the application!
if (PHP_SAPI == 'cli') {
	$serivceContainer->console->run();
} else {
	$container->application->run();
}
