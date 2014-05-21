<?php

# phpmig.php

use \Phpmig\Adapter;
use \Nette;

define('APP_DIR',  __DIR__ . '/../../app');
define('LIBS_DIR',  __DIR__ . '/../../libs');
define('TEMP_DIR',  __DIR__ . '/../../temp');
define('LOG_DIR',  __DIR__ . '/../../log');
define('VENDOR_DIR',  __DIR__ . '/../../vendor');
define('CENTRAL_LANGUAGE', 38);

require_once VENDOR_DIR . '/autoload.php';
require_once __DIR__ . '/../traits/ExecuteSQLFromFile.php';
require_once __DIR__ . '/../traits/ApplicationSettings.php';
require_once __DIR__ . '/../libs/Migration.php';
require_once APP_DIR . '/entityConstants.php';


$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

Nette\Diagnostics\Debugger::$strictMode = TRUE;
Nette\Diagnostics\Debugger::enable(FALSE);

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters([
	'centralLanguage' => CENTRAL_LANGUAGE,
	'appDir' => APP_DIR,
]);

//$configurator->setDebugMode(false);

$configurator->enableDebugger(LOG_DIR);


$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->addDirectory(TEMP_DIR . '/proxies')
	->register();


Extras\Config\PresenterExtension::register($configurator);

$configurator->addConfig(APP_DIR . '/configs/config.neon', FALSE);

if ($section !== 'production') {
	$configurator->addConfig(APP_DIR . '/configs/local.config.neon', FALSE);
} else {
	$logger = new \Diagnostics\ErrorLogger;
	$logger->email = $logEmail;
	$logger->directory = ROOT_DIR . '/log';
	Nette\Diagnostics\Debugger::$logger = $logger;
}

$configurator->addConfig(APP_DIR . '/configs/'.$section.'.config.neon', FALSE);
$configurator->addConfig(APP_DIR . '/configs/phpmig.config.neon');

$applicationContainer = $configurator->createContainer();


require_once APP_DIR . '/extras/EntityAnnotation.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(APP_DIR . '/extras/EntityAnnotation.php');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(callback('class_exists'));
\Doctrine\DBAL\Types\Type::addType('json', 'Doctrine\Types\Json');
\Doctrine\DBAL\Types\Type::addType('latlong', 'Doctrine\Types\LatLong');



$databaseConfig = $applicationContainer->parameters['leanConnectionInfo'];


$container = new Pimple();


$container['db'] = $container->share(function () use ($databaseConfig) {
	$dbh = new PDO('mysql:dbname=' . $databaseConfig['database'] . ';host=' . $databaseConfig['host'], $databaseConfig['user'], $databaseConfig['password']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
});


$container['phpmig.adapter'] = $container->share(function() use ($container) {
	return new Adapter\PDO\Sql($container['db'], '___phpmig_migrations');
});

$container['lean'] = $container->share(function() use ($applicationContainer) {
	return $applicationContainer->getByType('\LeanMapper\Connection');
});

$container['em'] = $container->share(function() use ($applicationContainer) {
	return $applicationContainer->getByType('\Kdyby\Doctrine\EntityManager');
});

$migrationsDis = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'migrations';
$migrations = [];
foreach(Nette\Utils\Finder::findFiles('*.php')->from($migrationsDis) as $fileName => $file) {
	$migrations[] = $fileName;
}

$container['phpmig.migrations'] = $migrations;

$container['dic'] = $applicationContainer;

return $container;
