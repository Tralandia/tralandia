<?php
namespace Extras\Models\Service;

/**
 * ServiceFactory class
 *
 * @author Dávid Ďurika
 */
class ServiceFactory extends \Nette\Object {

	public $model;
	public $serviceName;

	public function __construct(\Doctrine\ORM\EntityManager $model, $serviceName) {
		$this->model = $model;
		$this->serviceName = $serviceName;
	}

	public function create($entity) {
		$serviceName = $this->serviceName;
		return new $serviceName($this->model, $entity);
	}

}