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

			self::$nsm = new NestedSet\Manager($config);
		}
		return self::$nsm;
	}
	
	public static function getNsm() {
		return self::getNestedSetManager();
	}

	public function getNestedNode() {
		$nsm = self::getNsm();
		return $nsm->wrapNode($this->getMainEntity());

	}

	public function createRoot() {
		$nsm = self::getNsm();
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

}
