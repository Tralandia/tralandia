<?php

namespace Extras\Models;

class ServiceLoader {

	private static $aliases = array();
	private static $aliasesStack = array();

	public static function exists($key) {
		return isset(self::$aliases[$key]);
	}

	public static function set($key,Service $service) {
		self::$aliases[$key] = $service;
	}

	public static function get($key) {
		return self::$aliases[$key];
	}

	public static function addToStack(Service $service) {
		self::$aliasesStack[spl_object_hash($service)] = $service;
	}

	public static function flushStack() {
		foreach (self::$aliasesStack as $key => $val) {
			self::set(get_class($val) . '#' . $val->getId(), $val);
		}
		self::$aliasesStack = array();
	}
}