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

	public $parameters = NULL;

	public function __construct(\Doctrine\ORM\EntityManager $model, $serviceName, EntityFactory $entityFactory) {
		$this->model = $model;
		$this->serviceName = $serviceName;
		$this->entityFactory = $entityFactory;
	}

	public function create($entity = NULL) {
		$serviceName = $this->serviceName;
		if($entity === NULL && !func_num_args()) {
			$service = new $serviceName($this->model, $this->entityFactory->create());
		} else {
			$service = new $serviceName($this->model, $entity);
		}

		$service = $this->injectParametersToService($service);

		return $service;
	}

	public function setParameters() {
		$this->parameters = func_get_args();
	}

	protected function injectParametersToService($service) {
		if($this->parameters) {
			call_user_func_array(array($service, 'inject'), $this->parameters);
		}

		return $service;
	}

}