<?php

namespace Tra\Services;

class ServiceLoader {

	private static $aliases = array();

	public static function exists($key) {
		return isset(self::$aliases[$key]) && is_object(self::$aliases[$key]);
	}

	public static function set($key, $service) {
		self::$aliases[$key] = $service;
	}

	public static function get($key) {
		return self::$aliases[$key];
	}
}