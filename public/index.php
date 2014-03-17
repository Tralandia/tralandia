<?php
//ini_set('display_errors', '1'); error_reporting(E_ALL);


// logovanie user agentov
//$log = '/var/www/agents.log';
//error_log('[' . date('Y-m-d H:i:s') . ']' . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . $_SERVER['HTTP_USER_AGENT'] . "\t " . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL'] . PHP_EOL, 3, $log);

// the identification of this site
define('SITE', '');
define('CENTRAL_LANGUAGE', 38);

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

define('VENDOR_DIR', ROOT_DIR . '/vendor');


// absolute filesystem path to the libraries
define('TEMP_DIR', ROOT_DIR . '/temp');

define('STATIC_DOMAIN', 'http://tralandiastatic.com/');
require APP_DIR . '/entityConstants.php';

MyTimer::start();
// load bootstrap file
require APP_DIR . '/bootstrap.php';

MyTimer::end();


class MyTimer {

	public static $lastStamp = ['__' => 0];
	public static $log = [];

	public static function start()
	{
		self::$lastStamp['__'] = microtime(true);
	}

	public static function log($label)
	{
		$now = microtime(true);
		if(array_key_exists($label, self::$lastStamp)) {
			self::$log[$label] = $now - self::$lastStamp[$label];
			self::$lastStamp[$label] = $now;
		} else {
			self::$log[$label] = $now - self::$lastStamp['__'];
			self::$lastStamp['__'] = $now;
		}
	}

	public static function end()
	{
		self::log('end');
		self::report();
	}

	public function report()
	{
		echo '<table style="position: fixed; top:100px; left:0; z-index: 1000000; width: 300px;">';
		foreach(self::$log as $key => $value) {
			echo '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
		}
		echo '</table>';

	}
}
