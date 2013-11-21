<?php

use Nette\Diagnostics\Debugger;

// Absolute filesystem path to the web root
define('ROOT_DIR', __DIR__ . '/..');
define('TEMP_DIR', ROOT_DIR . '/temp');
define('LIBS_DIR', ROOT_DIR . '/libs');
define('APP_DIR', ROOT_DIR . '/app');
define('TESTS_DIR', ROOT_DIR . '/tests');
define('INCLUDE_DIR', TESTS_DIR . '/include');
define('CENTRAL_LANGUAGE', 38);
define('VENDOR_DIR', ROOT_DIR . '/vendor');

require APP_DIR . '/entityConstants.php';


$_SERVER['HTTP_HOST'] = 'localhost';

// Load Nette Framework
require_once VENDOR_DIR . '/autoload.php';

// Load configuration from config.neon
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$wwwDir = dirname(__FILE__) . '/../public';
$configurator->addParameters(array('appDir' => APP_DIR, 'wwwDir' => $wwwDir, 'centralLanguage' => CENTRAL_LANGUAGE));
$configurator->enableDebugger(ROOT_DIR . '/log');
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(__DIR__)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

require_once LIBS_DIR . '/tools.php';

Extras\Config\PresenterExtension::register($configurator);
Kdyby\Replicator\Container::register();
$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

$configurator->addConfig(APP_DIR . '/configs/config.neon', FALSE);
$configurator->addConfig(APP_DIR . '/configs/'.$section.'.config.neon', FALSE);
$configurator->addConfig(APP_DIR . '/configs/test.config.neon', FALSE);

$container = $configurator->createContainer();
require_once APP_DIR . '/extras/EntityAnnotation.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(__DIR__ . '/../app/extras/EntityAnnotation.php');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(callback('class_exists'));
\Doctrine\DBAL\Types\Type::addType('json', 'Doctrine\Types\Json');
\Doctrine\DBAL\Types\Type::addType('latlong', 'Doctrine\Types\LatLong');

# toto musi byt to dole!
Debugger::enable(FALSE);


//ob_start();
//$container->application->run();
