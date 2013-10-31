<?php

use Nette\Diagnostics\Debugger;
use Nette\Environment;
use Nette\Application\Routers\Route;
use Nette\Forms\Container as FormContainer;



if(array_key_exists('useCache', $_GET)) {
	setcookie("useCache", (int)$_GET['useCache'], strtotime('+1 year'));
	header("Location: /");
	die();
}

// Load Nette Framework
require_once VENDOR_DIR . '/autoload.php';


$section = isset($_SERVER['APPENV']) ? $_SERVER['APPENV'] : 'production';

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters([
	'centralLanguage' => CENTRAL_LANGUAGE,
]);



$configurator->setDebugMode(false);

$logEmail = 'durika.d@gmail.com';
$configurator->enableDebugger(ROOT_DIR . '/log', $logEmail);

$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->addDirectory(TEMP_DIR . '/presenters')
	->addDirectory(TEMP_DIR . '/proxies')
	->register();

// Kdyby\Extension\Forms\BootstrapRenderer\DI\RendererExtension::register($configurator);

require_once LIBS_DIR . '/tools.php';
Extras\Config\PresenterExtension::register($configurator);
Kdyby\Replicator\Container::register();

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


if(array_key_exists('useCache', $_COOKIE) && !$_COOKIE['useCache']) {
	$configurator->addConfig(APP_DIR . '/configs/noCache.config.neon', FALSE);
}

$dic = $container = $configurator->createContainer();

//$tablePrefix = new \DoctrineExtensions\TablePrefix(NULL, '_');
//$container->getService('doctrine.default.connection')->getEventManager()->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);


// Debugger::$editor = $container->parameters['editor'];

FormContainer::extensionMethod('addPhraseContainer',
	function (FormContainer $container, $name, $phrase) use ($dic) {
		$em = $dic->getService('doctrine.default.entityManager');
		$phraseManager = $dic->getByType('Tralandia\Dictionary\PhraseManager');
		$languages = $dic->getByType('\Tralandia\Language\Languages');
		return $container[$name] = new \Extras\Forms\Container\PhraseContainer($phrase, $phraseManager, $languages, $em);
	});

FormContainer::extensionMethod('addPhoneContainer',
	function (FormContainer $container, $name, $label, $phonePrefixes) use ($dic) {
		$translator = $dic->getService('translator');
		$phoneBook = $dic->getService('phoneBook');
		return $container[$name] = new \Extras\Forms\Container\PhoneContainer($label, $phonePrefixes, $phoneBook, $translator);
	});

FormContainer::extensionMethod('addRentalTypeContainer',
	function (FormContainer $container, $name, $rental, $rentalTypes) use ($dic) {
		$translator = $dic->getService('translator');
		$rentalTypeRepository = $dic->getService('doctrine.default.entityManager')->getDao(RENTAL_TYPE_ENTITY);
		return $container[$name] = new \Extras\Forms\Container\RentalTypeContainer($rental, $rentalTypes, $translator, $rentalTypeRepository);
	});

FormContainer::extensionMethod('addRentalPhotosContainer',
	function (FormContainer $container, $name, $rental = NULL) use ($dic) {
		$imageManager = $dic->getService('rentalImageManager');
		$imageRepository = $dic->getService('doctrine.default.entityManager')->getDao(RENTAL_IMAGE_ENTITY);
		return $container[$name] = new \Extras\Forms\Container\RentalPhotosContainer($rental, $imageManager, $imageRepository);
	});

FormContainer::extensionMethod('addPriceContainer',
	function (FormContainer $container, $name, $label) use ($dic) {
		$em = $dic->getService('doctrine.default.entityManager');
		$translator = $dic->getService('translator');
		$collator = $dic->getService('environment')->getLocale()->getCollator();
		return $container[$name] = new \Extras\Forms\Container\PriceContainer($label, $em, $translator, $collator);
	});

FormContainer::extensionMethod('addRentalPriceUploadContainer',
	function (FormContainer $container, $name, $rental = NULL) use ($dic) {
		$em = $dic->getService('doctrine.default.entityManager');
		$manager = $dic->getService('rentalPriceListManager');
		$translator = $dic->getService('translator');
		$allLanguages = $dic->getService('allLanguages');
		return $container[$name] = new \Extras\Forms\Container\RentalPriceUploadContainer($rental, $manager, $allLanguages, $translator, $em);
	});

FormContainer::extensionMethod('addRentalPriceListContainer',
	function (FormContainer $container, $name, $currency, $rental) use ($dic) {
		$em = $dic->getService('doctrine.default.entityManager');
		$translator = $dic->getService('translator');
		$collator = $dic->getService('environment')->getLocale()->getCollator();
		return $container[$name] = new \Extras\Forms\Container\RentalPriceListContainer($currency, $em, $rental, $translator, $collator);
	});

FormContainer::extensionMethod('addCalendarContainer',
	function (FormContainer $container, $name, $label, $selectedDays = NULL) use ($dic) {
		$locale = $dic->getService('environment')->getLocale();
		return $container[$name] = new \Extras\Forms\Container\CalendarContainer($label, $locale, $selectedDays);
	});

FormContainer::extensionMethod('addAddressContainer',
	function (FormContainer $container, $name, $addressOrLocation) use ($dic) {
		$addressCreator = $dic->getService('addressCreator');
		$translator = $dic->getService('translator');
		return $container[$name] = new \Extras\Forms\Container\AddressContainer($addressOrLocation, $addressCreator, $translator);
	});


require_once APP_DIR . '/extras/EntityAnnotation.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(__DIR__ . '/../app/extras/EntityAnnotation.php');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(callback('class_exists'));
\Doctrine\DBAL\Types\Type::addType('json', 'Doctrine\Types\Json');
\Doctrine\DBAL\Types\Type::addType('latlong', 'Doctrine\Types\LatLong');

// Run the application!
$container->application->run();
