<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nella\Addons\Doctrine\Config\Extension;


// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
require_once LIBS_DIR . '/rado_functions.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable();
Debugger::$strictMode = TRUE;
Debugger::$maxLen = 2000;
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


$configurator->addConfig(APP_DIR . '/configs/config.neon');
$configurator->addConfig(APP_DIR . '/configs/local.config.neon');
if($section) {
	$configurator->addConfig(APP_DIR . '/configs/'.$section.'.config.neon');
}
$configurator->onCompile[] = function ($configurator, $compiler) {
	Extras\PresenterGenerator::generate();
	$compiler->addExtension('gpspicker', new VojtechDobes\NetteForms\GpsPickerExtension);
};
$container = $configurator->createContainer();


// @todo toto niekam schovat
Panel\Todo::register($container->parameters['appDir']);
require_once APP_DIR . '/extras/EntityAnnotation.php';

//Extras\Models\Service::$translator = $container->translator;

// Run the application!
if (PHP_SAPI == 'cli') {
	$container->console->run();
} else {
	$container->application->run();
}
