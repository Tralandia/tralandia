<?php

namespace Extras;

use Nette\Config;


class Configurator extends Config\Configurator {

	/**
	 * @return array
	 */
	protected function getDefaultParameters() {
		$trace = debug_backtrace(FALSE);
		return array(
			'appDir' => isset($trace[1]['file']) ? dirname($trace[1]['file']) : NULL,
			'wwwDir' => isset($_SERVER['SCRIPT_FILENAME']) ? dirname($_SERVER['SCRIPT_FILENAME']) : NULL,
			'productionMode' => static::detectProductionMode(),
			'environment' => static::detectProductionMode() ? self::PRODUCTION : self::DEVELOPMENT,
			'consoleMode' => PHP_SAPI === 'cli',
			'container' => array(
				'class' => 'ServiceContainer',
				'parent' => 'Nette\DI\Container',
			)
		);
	}

}