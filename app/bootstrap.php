<?php

use Nette\Diagnostics\Debugger,
	Nette\Environment,
	Nette\Application\Routers\Route,
	Nella\Addons\Doctrine\Config\Extension;
use Nette\Forms\Container as FormContainer;


// Load Nette Framework
require_once LIBS_DIR . '/Doctrine/Common/EventManager.php';
require_once VENDOR_DIR . '/autoload.php';
require_once LIBS_DIR . '/rado_functions.php';

// Enable Nette\Debug for error visualisation & logging
//Debugger::enable(Debugger::PRODUCTION);
Debugger::enable();
//Debugger::$strictMode = TRUE;

$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : NULL;

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters(array('centralLanguage' => CENTRAL_LANGUAGE));
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

$configurator->addConfig(APP_DIR . '/configs/config.neon', FALSE);
if(!Debugger::$productionMode) {
	$configurator->addConfig(APP_DIR . '/configs/local.config.neon', FALSE);
}

if (isset($_SERVER['REDIRECT_URL']) && ($_SERVER['REDIRECT_URL'] == '/import' || $_SERVER['REDIRECT_URL'] == '/import/import/default')) {
	$section = 'import';
}

if ($section) {
	$configurator->addConfig(APP_DIR . '/configs/'.$section.'.config.neon', FALSE);
}
$configurator->onCompile[] = function ($configurator, $compiler) {
	$compiler->addExtension('events', new Kdyby\Events\DI\EventsExtension);
};
$dic = $container = $configurator->createContainer();
// Debugger::$editor = $container->parameters['editor'];

FormContainer::extensionMethod('addPhoneContainer',
	function (FormContainer $container, $name, $label, $phonePrefixes) use ($dic) {
		$translator = $dic->getService('translator');
		$phoneBook = $dic->getService('phoneBook');
		return $container[$name] = new \Extras\Forms\Container\PhoneContainer($label, $phonePrefixes, $phoneBook, $translator);
});

FormContainer::extensionMethod('addRentalTypeContainer',
	function (FormContainer $container, $name, $rentalTypes) use ($dic) {
		$translator = $dic->getService('translator');
		return $container[$name] = new \Extras\Forms\Container\RentalTypeContainer($rentalTypes, $translator);
});

FormContainer::extensionMethod('addRentalPhotosContainer',
	function (FormContainer $container, $name, $rental = NULL) use ($dic) {
		$rentalImageManager = $dic->getService('rentalImageManager');
		return $container[$name] = new \Extras\Forms\Container\RentalPhotosContainer($rental, $rentalImageManager);
});


// @todo toto niekam schovat
// Panel\Todo::register($container->parameters['appDir']);
require_once APP_DIR . '/extras/EntityAnnotation.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(__DIR__ . '/../app/extras/EntityAnnotation.php');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(callback('class_exists'));

//Extras\Models\Service::$translator = $container->translator;

// Run the application!
if (PHP_SAPI == 'cli') {
	$container->console->run();
} else {
	$container->application->run();
}
