<?php
namespace Extras\Models\Service;

use Doctrine\ORM\EntityManager;
use Extras\Models\Entity\EntityFactory;

/**
 * ServiceFactory class
 *
 * @author Dávid Ďurika
 */
class ServiceFactory extends \Nette\Object {

	public $model;
	public $serviceName;
	public $entityFactory;

	public function __construct(\Doctrine\ORM\EntityManager $model, $serviceName, EntityFactory $entityFactory) {
		$this->model = $model;
		$this->serviceName = $serviceName;
		$this->entityFactory = $entityFactory;
	}

	public function create($entity = NULL) {
		$serviceName = $this->serviceName;
		if($entity === NULL && !func_num_args()) {
			return new $serviceName($this->model, $this->entityFactory->create());
		} else {
			return new $serviceName($this->model, $entity);
		}
	}

}