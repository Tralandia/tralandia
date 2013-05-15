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

$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters([
	'centralLanguage' => CENTRAL_LANGUAGE,
]);


// $configurator->setDebugMode(false);

$logEmail = 'durika.d@gmail.com';
$configurator->enableDebugger(ROOT_DIR . '/log', $logEmail);

$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->register();

// Kdyby\Extension\Forms\BootstrapRenderer\DI\RendererExtension::register($configurator);

require_once LIBS_DIR . '/tools.php';
Extension::register($configurator);
Extras\Config\PresenterExtension::register($configurator);
Kdyby\Replicator\Container::register();
Kdyby\Redis\DI\RedisExtension::register($configurator);

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

if (isset($_SERVER['REDIRECT_URL']) && ($_SERVER['REDIRECT_URL'] == '/import' || $_SERVER['REDIRECT_URL'] == '/import/import/default')) {
	$configurator->addConfig(APP_DIR . '/configs/import.config.neon', FALSE);
}

$dic = $container = $configurator->createContainer();
// Debugger::$editor = $container->parameters['editor'];

FormContainer::extensionMethod('addPhraseContainer',
	function (FormContainer $container, $name, $phrase) use ($dic) {
		$em = $dic->getService('model');
		return $container[$name] = new \Extras\Forms\Container\PhraseContainer($phrase, $em);
	});

FormContainer::extensionMethod('addPhoneContainer',
	function (FormContainer $container, $name, $label, $phonePrefixes) use ($dic) {
		$translator = $dic->getService('translator');
		$phoneBook = $dic->getService('phoneBook');
		return $container[$name] = new \Extras\Forms\Container\PhoneContainer($label, $phonePrefixes, $phoneBook, $translator);
	});

FormContainer::extensionMethod('addRentalTypeContainer',
	function (FormContainer $container, $name, $rentalTypes) use ($dic) {
		$translator = $dic->getService('translator');
		$rentalTypeRepository = $dic->getService('rentalTypeRepositoryAccessor')->get();
		return $container[$name] = new \Extras\Forms\Container\RentalTypeContainer($rentalTypes, $translator, $rentalTypeRepository);
	});

FormContainer::extensionMethod('addRentalPhotosContainer',
	function (FormContainer $container, $name, $rental = NULL) use ($dic) {
		$imageManager = $dic->getService('rentalImageManager');
		$imageRepository = $dic->getService('rentalImageRepositoryAccessor')->get();
		return $container[$name] = new \Extras\Forms\Container\RentalPhotosContainer($rental, $imageManager, $imageRepository);
	});

FormContainer::extensionMethod('addPriceContainer',
	function (FormContainer $container, $name, $label) use ($dic) {
		$em = $dic->getService('model');
		$translator = $dic->getService('translator');
		$collator = $dic->getService('environment')->getLocale()->getCollator();
		return $container[$name] = new \Extras\Forms\Container\PriceContainer($label, $em, $translator, $collator);
	});

FormContainer::extensionMethod('addRentalPriceUploadContainer',
	function (FormContainer $container, $name, $rental = NULL) use ($dic) {
		$em = $dic->getService('model');
		$manager = $dic->getService('rentalPriceListManager');
		return $container[$name] = new \Extras\Forms\Container\RentalPriceUploadContainer($rental, $manager, $em);
	});

FormContainer::extensionMethod('addRentalPriceListContainer',
	function (FormContainer $container, $name, $currency, $rental) use ($dic) {
		$em = $dic->getService('model');
		$translator = $dic->getService('translator');
		$collator = $dic->getService('environment')->getLocale()->getCollator();
		return $container[$name] = new \Extras\Forms\Container\RentalPriceListContainer($currency, $em, $rental, $translator, $collator);
	});

FormContainer::extensionMethod('addAddressContainer',
	function (FormContainer $container, $name, $addressOrLocation) use ($dic) {
		$addressCreator = $dic->getService('addressCreator');
		return $container[$name] = new \Extras\Forms\Container\AddressContainer($addressOrLocation, $addressCreator);
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
