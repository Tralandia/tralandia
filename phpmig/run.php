<?php

use Kdyby\Events\DI\EventsExtension;
use Nette\Diagnostics\Debugger;

date_default_timezone_set('Europe/Prague');

define('APP_DIR',  __DIR__ . '/../app');
define('VENDOR_DIR',  __DIR__ . '/../vendor');
define('CONFIG_DIR',  __DIR__ . '/config');

iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');


$command = isset($argv[1]) ? $argv[1] : 'migrate';
$option = isset($argv[2]) ? $argv[2] : NULL;
$optionValue = isset($argv[3]) ? $argv[3] : NULL;


$command = VENDOR_DIR . "/bin/phpmig {$command} {$option} {$optionValue} -b ". CONFIG_DIR . '/bootstrap.php';
echo $command . "\n";
system($command);
