<?php

namespace Extras\Reflection\Entity;

class ClassType extends \Extras\Reflection\ClassType {

	protected $propertyClass = '\Extras\Reflection\Entity\Property';
	
	const ANN_COLUMN = 'ORM\Column';
	const ANN_ONE_TO_ONE = 'ORM\OneToOne';
	const ANN_MANY_TO_ONE = 'ORM\ManyToOne';
	const ANN_ONE_TO_MANY = 'ORM\OneToMany';
	const ANN_MANY_TO_MANY = 'ORM\ManyToMany';

	const COLUMN_CONTACTS = 'contacts';
	const COLUMN_PHONE = 'phone';
	const COLUMN_NAME = 'name';
	const COLUMN_ADDRESS = 'address';
	const COLUMN_EMAIL = 'email';
	const COLUMN_SKYPE = 'skype';
	const COLUMN_URL = 'url';
	
	const COLUMN_PHRASE = 'Entity\Dictionary\Phrase';

	const GROUP_CONTACTS = 1;

	private $groups = array(
			self::GROUP_CONTACTS => array(
				self::COLUMN_CONTACTS,
				self::COLUMN_PHONE,
				self::COLUMN_NAME,
				self::COLUMN_ADDRESS,
				self::COLUMN_EMAIL,
				self::COLUMN_SKYPE,
				self::COLUMN_URL,
			),
		);

	public function getPropertiesByType($type, $filter = -1) {
		if($type == self::GROUP_CONTACTS) $type = $this->groups[self::GROUP_CONTACTS];
		if(!is_array($type)) $type = array($type);

		$return = array();
		$properties = $this->getProperties($filter);
		debug($properties);
		foreach ($properties as $property) {
			if($column = $property->getAnnotation(self::ANN_COLUMN)) {
				debug($column);
				$columnType = $column->type;
				if($columnType && in_array($columnType, $type)) {
					$return[] = $property;
				}
			}
		}

		return $return;
	}

}