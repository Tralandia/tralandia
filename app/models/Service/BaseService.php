<?php

namespace Service;

class BaseService extends \Extras\Models\Service {

	public static function getAsService($variable, $type) {
		$entityType = $type::MAIN_ENTITY_NAME;

		if($variable instanceof $type) {
			return $variable;
		} else if($variable instanceof $entityType) {
			return $type::get($variable);
		} else if(is_integer($variable) && $variable > 0) {
			return $type::get($variable);
		} else {
			throw new \Nette\InvalidArgumentException('$variable argument does not match with the expected value');
		}

	}

	public static function getSuggestions($property, $srerch, $translate) {
		$entityName = static::getMainEntityName();

		$serviceList = new static;
		$qb = $serviceList->getEntityManager()->createQueryBuilder();

		if($translate) {
			$select = array('e.id', 'p.id AS value');
		} else {
			$select = array('e.id', 'e.'.$property.' AS value');
		}
		$qb->select($select)
			->from($entityName, 'e');

		if($translate) $qb->join('e.'.$property, 'p');

	}
}
