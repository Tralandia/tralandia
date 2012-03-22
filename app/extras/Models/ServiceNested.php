<?php

namespace Extras\Models;

use DoctrineExtensions\NestedSet;

/**
 * Abstrakcia mocnej vrstvy sluzba
 */
abstract class ServiceNested extends Service {

	protected static $nsm = NULL;


	public static function getNestedSetManager() {
		if(self::$nsm === NULL) {
			$config = new NestedSet\Config(self::getEm(), self::getMainEntityName());
			self::$nsm = new NestedSet\Manager($config);
		}
		return self::$nsm;
	}
	
	public static function getNsm() {
		return self::getNestedSetManager();
	}

}
