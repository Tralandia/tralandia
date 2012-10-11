<?php
namespace Extras\Models;

/**
 * ServiceFactory class
 *
 * @author Dávid Ďurika
 */
class ModelFactory extends \Nette\Object {

	public $model;
	public $serviceName;
	public $entityName;

	public function __construct(\Doctrine\ORM\EntityManager $model, $name) {
		$this->model = $model;
		$this->serviceName = 'Service\\' . $name . 'Service';
		$this->entityName = 'Entity\\' . $name;
	}

	public function createEntity() {
		$entityName = $this->entityName;
		return new $entityName;
	}

	public function createService($entity = NULL) {
		$serviceName = $this->serviceName;
		if($entity === NULL && !func_num_args()) {
			return new $serviceName($this->model, $this->createEntity());
		}else if($entity instanceof $this->entityName) {
			return new $serviceName($this->model, $entity);
		} else {
			throw new \Nette\InvalidArgumentException('Argument "$entity" does not match with the expected value');
		}
	}

}