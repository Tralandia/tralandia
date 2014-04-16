<?php

# phpmig.php

use \Phpmig\Adapter;
use \Nette;


require_once VENDOR_DIR . '/autoload.php';

$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters([
	'centralLanguage' => CENTRAL_LANGUAGE,
]);

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



$container = new Pimple();

$container['db'] = $container->share(function() {
	$dbh = new PDO('mysql:dbname=tralandia;host=127.0.0.1','root','root');
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
});

$container['phpmig.adapter'] = $container->share(function() use ($container) {
	return new Adapter\PDO\Sql($container['db'], 'phpmig_migrations');
});

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
