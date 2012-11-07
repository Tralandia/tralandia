<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nella\Addons\Doctrine\Config\Extension;


// Load Nette Framework
require_once LIBS_DIR . '/Nette/nette.min.php';
require_once LIBS_DIR . '/rado_functions.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable();
Debugger::$strictMode = TRUE;
$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : null;

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableDebugger(ROOT_DIR . '/log');
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

// Kdyby\Extension\Forms\BootstrapRenderer\DI\RendererExtension::register($configurator);
require_once LIBS_DIR . '/tools.php';
Extension::register($configurator);
Extras\Config\PresenterExtension::register($configurator);
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$configurator->onCompile[] = callback('Extras\PresenterGenerator', 'generate');
$container = $configurator->createContainer();

Panel\Todo::register($container->parameters['appDir']);

// @todo toto niekam schovat
require_once APP_DIR . '/extras/EntityAnnotation.php';
//Extras\Models\Service::$translator = $container->translator;

// Setup router // TODO: presunut do config.neon
$container->application->onStartup[] = function() use ($container) {
	$router = $container->application->getRouter();

	$router[] = $gregor = new RouteList('gregor');
	$gregor[] = new Route('gregor/[<presenter>/[<action>[/<id>]]]', array(
		'presenter' => 'Page',
		'action' =>  'home'
	));

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
	$container->console->run();
} else {
	$container->application->run();
}
