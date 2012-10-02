<?php

namespace Extras\FormMask;

use Nette;
use Extras;
use Nette\Utils\Arrays;

class Generator extends Nette\Object {

	protected $entityReflection;

	protected $config;

	public function __construct(Extras\Reflection\Entity\ClassType $entityReflection, $config) {
		$this->entityReflection = $entityReflection;
		$this->config = $config;		
	}

	public function fillMask(Mask $mask) {
		$fields = $this->config['form']['fields'];
		d($fields);
		foreach ($fields as $fieldName => $field) {
			$type = $this->getControlType($field);
			$itemType = $this->getMaskItemType($type);
			//$mask->add(constant('Mask::'.))
		}

		return $mask;
	}

	public static function factory(Mask $mask, Extras\Reflection\Entity\ClassType $entityReflection, $config) {
		$generator = new static($entityReflection, $config);
		$mask = $generator->fillMask($mask);
		return $mask;
	}

	protected function getControlType($field) {
		$type = Arrays::get($field, array('control', 'type'), NULL);
		if(!$type) {
			throw new \Nette\InvalidArgumentException('Pre ' . $field['label'] . ' si nenastavil "control:type"');
		}
		return $type;
	}

	protected function getMaskItemType($type) {

	}

}