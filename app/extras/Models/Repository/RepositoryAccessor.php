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

	/**
	 * @param EntityManager $em         
	 * @param string        $entityName namespace entity
	 */
	public function __construct(EntityManager $em, $entityName) {
		list($this->em, $this->entityName) = func_get_args();
	}

	public function get() {
		// netreba ukladat (cachovat) vytvorenu instanciu, robi to za nas doctrine
		return $this->em->getRepository($this->entityName);
	}

}