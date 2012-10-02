<?php

namespace Extras\Forms;

use Nette;
use Extras;

class MaskGenerator extends \Nette\Object {

	protected $entityReflection;

	protected $config;

	public function __construct(Extras\Reflection\Entity\ClassType $entityReflection, $config) {
		$this->entityReflection = $entityReflection;
		$this->config = $config;		
	}

	public function fillMask(Mask $mask) {
		$fields = $this->config['form']['fields'];
		foreach ($fields as $fieldName => $field) {
			d($field);
		}

		return $mask;
	}

	public static function factory(Mask $mask, Extras\Reflection\Entity\ClassType $entityReflection, $config) {
		$generator = new static($entityReflection, $config);
		$mask = $generator->fillMask($mask);
		return $mask;
	}

}