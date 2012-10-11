<?php
namespace Extras\Models\Entity;

/**
 * EntityFactory class
 *
 * @author Dávid Ďurika
 */
class EntityFactory extends \Nette\Object {

	public $entityName;

	public function __construct($entityName) {
		$this->entityName = $entityName;
	}

	public function create() {
		$entityName = $this->entityName;
		return new $entityName;
	}

}