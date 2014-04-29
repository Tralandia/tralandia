<?php


define('ROOT_DIR', __DIR__ . '/..');
//define('TEMP_DIR', ROOT_DIR . '/temp');
define('LIBS_DIR', ROOT_DIR . '/libs');
define('APP_DIR', ROOT_DIR . '/app');
define('LOG_DIR',  ROOT_DIR . '/log');
define('TESTS_DIR', ROOT_DIR . '/tests');
define('INCLUDE_DIR', TESTS_DIR . '/include');
define('CENTRAL_LANGUAGE', 38);
define('VENDOR_DIR', ROOT_DIR . '/vendor');

require APP_DIR . '/entityConstants.php';

require_once VENDOR_DIR . '/autoload.php';


$configurator = new \Nette\Config\Configurator;
//$configurator->enableDebugger(FALSE);
//$configurator->setDebugMode(FALSE, LOG_DIR);
$configurator->setTempDirectory(TEMP_DIR);

$configurator->addParameters([
	'centralLanguage' => CENTRAL_LANGUAGE,
	'appDir' => APP_DIR,
	'wwwDir' => ROOT_DIR . '/public',
]);

$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(__DIR__)
	->addDirectory(LIBS_DIR)
	->addDirectory(ROOT_DIR . '/temp/presenters')
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
\Nette\Diagnostics\Debugger::enable(FALSE);

return $container;
