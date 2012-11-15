<?php
namespace Factory;

use Doctrine\ORM\EntityManager;
use Extras\Models\Entity\EntityFactory;

/**
 * BaseServiceFactory class
 *
 * @author Dávid Ďurika
 */
abstract class BaseServiceFactory {

	public $model;
	public $entityFactory;


	public function __construct(\Doctrine\ORM\EntityManager $model, $entityFactory = NULL) {
		$this->model = $model;
		$this->entityFactory = $entityFactory;
	}

	public function create($entity = NULL) {
		if(!func_num_args()) {
			if($this->entityFactory === NULL) {
				throw new \Nette\InvalidArgumentException('Musis vlozit entitu!');
			} else {
	 			$service = $this->createInstance($this->model, $this->entityFactory->create());
			}
		} else {
			if($entity === NULL) {
				throw new \Nette\InvalidArgumentException('Vlozil "prazdnu" entitu!');
			} else {
				$service = $this->createInstance($this->model, $entity);
			}
		}

		return $service;
	}

	abstract protected function createInstance($model, $entity);

}