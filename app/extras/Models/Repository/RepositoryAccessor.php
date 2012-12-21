<?php
namespace Extras\Models\Repository;

use Doctrine\ORM\EntityManager;

/**
 * RepositoryAccessor class
 *
 * @author Dávid Ďurika
 */
class RepositoryAccessor {

	protected $em, $entityName;

	protected $instance;

	public $parameters = NULL;

	/**
	 * @param EntityManager $em         
	 * @param string        $entityName namespace entity
	 */
	public function __construct(EntityManager $em, $entityName) {
		list($this->em, $this->entityName) = func_get_args();
	}

	/**
	 * @return \Repository\BaseRepository
	 */
	public function get() {
		if(!$this->instance) {
			$this->instance = $this->em->getRepository($this->entityName);
			$this->injectParametersToInstance();
		}

		return $this->instance;
	}

	public function setParameters() {
		$this->parameters = func_get_args();
	}

	protected function injectParametersToInstance() {
		if($this->parameters) {
			call_user_func_array(array($this->instance, 'inject'), $this->parameters);
		}
	}

}