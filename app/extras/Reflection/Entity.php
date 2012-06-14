<?php

namespace Extras\Reflection;

use Nette\Reflection\ClassType;

class Entity extends ClassType {
	
	const ANN_COLUMN = 'ORM\Column';

	const COLUMN_CONTACTS = 'contacts';
	const COLUMN_PHONE = 'phone';
	const COLUMN_NAME = 'name';
	const COLUMN_ADDRESS = 'address';
	const COLUMN_EMAIL = 'email';
	const COLUMN_SKYPE = 'skype';
	const COLUMN_URL = 'url';

	const GROUP_CONTACTS = 1;

	private $groupContsctsTypes = array(
			self::COLUMN_CONTACTS,
			self::COLUMN_PHONE,
			self::COLUMN_NAME,
			self::COLUMN_ADDRESS,
			self::COLUMN_EMAIL,
			self::COLUMN_SKYPE,
			self::COLUMN_URL,
		);

	public function getPropertiesByType($type, $filter = -1) {
		if($type == self::GROUP_CONTACTS) $type = $this->groupContsctsTypes;
		if(!is_array($type)) $type = array($type);

		$return = array();
		$properties = $this->getProperties($filter);
		foreach ($properties as $property) {
			if($column = $property->getAnnotation(self::ANN_COLUMN)) {
				$columnType = $column->type;
				if($columnType && in_array($columnType, $type)) {
					$return[] = $property;
				}
			}
		}

		return $return;
	}

}