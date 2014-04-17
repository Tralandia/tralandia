<?php

# phpmig.php

use \Phpmig\Adapter;
use \Nette;

define('APP_DIR',  __DIR__ . '/../app');
define('TEMP_DIR',  __DIR__ . '/../temp');
define('VENDOR_DIR',  __DIR__ . '/../vendor');

require_once VENDOR_DIR . '/autoload.php';

$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
//$configurator->addParameters([
//	'centralLanguage' => CENTRAL_LANGUAGE,
//]);

$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
//	->addDirectory(LIBS_DIR)
//	->addDirectory(TEMP_DIR . '/presenters')
//	->addDirectory(TEMP_DIR . '/proxies')
	->register();


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

if(php_sapi_name() == 'cli') {
	$configurator->addConfig(__DIR__ . '/configs/cli.config.neon');
}

$applicationContainer = $configurator->createContainer();
$databaseConfig = $applicationContainer->parameters['leanConnectionInfo'];


$container = new Pimple();


$container['db'] = $container->share(function () use ($databaseConfig) {
	$dbh = new PDO('mysql:dbname=' . $databaseConfig['database'] . ';host=' . $databaseConfig['host'], $databaseConfig['user'], $databaseConfig['password']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
});


$container['phpmig.adapter'] = $container->share(function() use ($container) {
	return new Adapter\PDO\Sql($container['db'], 'phpmig_migrations');
});

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
