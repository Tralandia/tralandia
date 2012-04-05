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
			$config->setLeftFieldName('nestedLeft');
			$config->setRightFieldName('nestedRight');
			$config->setRootFieldName('nestedRoot');

			static::$nsm = new NestedSet\Manager($config);
		}
		return static::$nsm;
	}
	
	public static function getNsm() {
		return static::getNestedSetManager();
	}

	public function getNestedNode() {
		$nsm = static::getNsm();
		return $nsm->wrapNode($this->getMainEntity());

	}

	public function createRoot() {
		$nsm = static::getNsm();
		return $nsm->createRoot($this->getMainEntity());
	}

	public function addChild($child) {
		if($child instanceof Service) {
			$child = $child->getMainEntity();
		} else if($child instanceof Entity) {

		} else {
			throw new \Nette\InvalidArgumentException('$child argument does not match with the expected value');
		}

		$wrapNode = $this->getNestedNode();
		return $wrapNode->addChild($child);
	}

	public function fetchBranchAsArray($depth = NULL) {
		$nsm = static::getNsm();
		return $nsm->fetchBranchAsArray($this->id, $depth);
	}

}
