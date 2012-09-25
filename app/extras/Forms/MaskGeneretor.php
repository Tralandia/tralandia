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

}