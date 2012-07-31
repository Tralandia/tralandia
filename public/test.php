<?php

// the identification of this site
define('SITE', '');

// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__));

// absolute filesystem path to the root directory
define('ROOT_DIR', WWW_DIR . '/..');

// absolute filesystem path to the files
define('FILES_DIR', WWW_DIR . '/storage');

// absolute filesystem path to the application root
define('APP_DIR', ROOT_DIR . '/app');

// absolute filesystem path to the libraries
define('LIBS_DIR', ROOT_DIR . '/libs');

// absolute filesystem path to the libraries
define('TEMP_DIR', ROOT_DIR . '/temp');

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nella\Addons\Doctrine\Config\Extension;


// Load Nette Framework
require_once LIBS_DIR . '/Nette/loader.php';
require_once LIBS_DIR . '/rado_functions.php';

// Enable Nette\Debug for error visualisation & logging
Debugger::enable(FALSE);
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

require_once LIBS_DIR . '/tools.php';
Extension::register($configurator);
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$configurator->onCompile[] = callback('Extras\PresenterGenerator', 'generate');
$container = $configurator->createContainer();

//---------------------------------------------------------------------------------//



$entity = new Entity\Currency;
$entity->setIso('EUR')
	->setExchangeRate(44.66)
	->setRounding(2);

$container->createServiceCurrency($entity)->save();

$entity = $container->model->getRepository('Entity\Currency')->find($entity->getId());
$entity->setIso('CZK')
	->setRounding(14)
	->setRounding(987);

$service = $container->createServiceCurrency($entity);
$service->save();
$service->delete();




