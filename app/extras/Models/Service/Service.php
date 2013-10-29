<?php

namespace Extras\Models\Service;

use Nette, Doctrine, Extras;

/**
 * Vrstva sluzieb
 * @author Branislav Vaculčiak
 */
abstract class Service extends Nette\Object implements IService {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager = null;

	/**
	 * @var \Entity\BaseEntity
	 */
	protected $entity = null;


	/**
	 * @param \Doctrine\ORM\EntityManager $entityManager
	 * @param \Entity\BaseEntity $entity
	 */
	public function __construct(Doctrine\ORM\EntityManager $entityManager, \Entity\BaseEntity $entity) {
		$this->entityManager = $entityManager;
		$this->entity = $entity;
	}

	/**
	 * Vrati entity manazera
	 * @return Doctrine\ORM\EntityManager
	 */
	public function getEntityManager() {
		return $this->entityManager;
	}

	/**
	 * Vrati repozitar pre entitu
	 * @return Doctrine\ORM\EntityRepository
	 */
	public function getRepository() {
		return $this->entityManager->getRepository(get_class($this->entity));
	}

	/**
	 * Vrati entitu
	 * @return IEntity
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 * Ulozenie meny
	 * @return bool
	 */
	public function save($flush = true) {
		try {
			$this->getEntityManager()->persist($this->entity);
			if ($flush) $this->getEntityManager()->flush($this->entity);
			return true;
		} catch (Exception $e) {
			// @todo brano tu by som normalne vyhodil vynimku, ci ?
			return false;
		}
	}

	/**
	 * Vymazanie meny
	 * @return bool
	 */
	public function delete($flush = true) {
		try {
			$this->getEntityManager()->remove($this->entity);
			if ($flush) $this->getEntityManager()->flush($this->entity);
			return true;
		} catch (Exception $e) {
			// @todo brano tu by som normalne vyhodil vynimku, ci ?
			return false;
		}
	}
}
