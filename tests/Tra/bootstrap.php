<?php

/**
 * Test initialization and helpers.
 *
 * @author     David Grudl
 * @author     Branislav Vaculčiak
 * @package    Nette\Test
 */

// absolute filesystem path to the web root
define('ROOT_DIR', dirname(__FILE__) . '/../../');
define('WWW_DIR', ROOT_DIR . '/public');
define('APP_DIR', ROOT_DIR . '/app');
define('LIBS_DIR', ROOT_DIR . '/libs');
define('TEMP_DIR', ROOT_DIR . '/temp');
define('TESTS_DIR', ROOT_DIR . '/tests');

require TESTS_DIR . '/Test/TestHelpers.php';
require TESTS_DIR . '/Test/Assert.php';
require TESTS_DIR . '/extras/Assert.php';
require LIBS_DIR . '/Nette/loader.php';

use Nella\Addons\Doctrine\Config\Extension;

// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableDebugger(ROOT_DIR . '/log');

// Enable RobotLoader - this will load all classes automatically
$robotLoader = $configurator->createRobotLoader();
$robotLoader->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();


// Create Dependency Injection container from config.neon file
$configurator->addConfig(APP_DIR . '/configs/config.neon', $section);
$container = $configurator->createContainer();


$serviceConfigurator = new Extras\Configurator;
$serviceConfigurator->setTempDirectory(TEMP_DIR);

Extension::register($serviceConfigurator);

$serviceConfigurator->addConfig(APP_DIR . '/configs/service.neon', $section);
$serivceContainer = $serviceConfigurator->createContainer();

// Setup doctrine loader
$serivceContainer->createService();
$serivceContainer->createList();

// configure environment
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', TRUE);
ini_set('html_errors', FALSE);
ini_set('log_errors', FALSE);

// catch unexpected errors/warnings/notices
set_error_handler(function($severity, $message, $file, $line) {
	if (($severity & error_reporting()) === $severity) {
		echo ("Error: $message in $file:$line");
		exit(TestCase::CODE_ERROR);
	}
	return FALSE;
});

function debug() {}


$_SERVER = array_intersect_key($_SERVER, array_flip(array('PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS')));
$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = array();

if (PHP_SAPI !== 'cli') {
	header('Content-Type: text/plain; charset=utf-8');
}