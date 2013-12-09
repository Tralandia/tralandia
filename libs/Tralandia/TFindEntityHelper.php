<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/15/13 2:24 PM
 */

trait TFindEntityHelper {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $_em;


	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function initializeFindEntityHelper(\Doctrine\ORM\EntityManager $em)
	{
		$this->_em = $em;
	}

	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Rental\Rental|null
	 */
	public function findRental($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(RENTAL_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Rental\Type|null
	 */
	public function findRentalType($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(RENTAL_TYPE_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Rental\Pricelist|null
	 */
	public function findPricelist($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(RENTAL_PRICELIST_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Rental\Amenity|null
	 */
	public function findAmenity($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(RENTAL_AMENITY_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Location\Location|null
	 */
	public function findLocation($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(LOCATION_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Language|null
	 */
	public function findLanguage($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(LANGUAGE_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Rental\AmenityType|null
	 */
	public function findAmenityType($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(AMENITY_TYPE_ENTITY, $id, $need, $by);

	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Phrase\Phrase|null
	 */
	public function findPhrase($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(PHRASE_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Phrase\Translation|null
	 */
	public function findTranslation($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(TRANSLATION_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Page|null
	 */
	public function findPage($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(PAGE_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\Page|null
	 */
	public function findBoard($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(RENTAL_AMENITY_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $id
	 * @param bool $need
	 * @param string $by
	 *
	 * @return \Entity\User\User|null
	 */
	public function findUser($id, $need = TRUE, $by = 'id')
	{
		return $this->findHelper(USER_ENTITY, $id, $need, $by);
	}


	/**
	 * @param $entityName
	 * @param $id
	 * @param bool $need
	 * @param $by
	 *
	 * @throws Exception
	 * @return object
	 */
	private function findHelper($entityName, $id, $need, $by)
	{
		$entity = $this->_em->getRepository($entityName)->findOneBy([$by => $id]);
		if(!$entity && $need) {
			throw new \Exception("{$entityName}::{$by} == {$id} not exists.");
		}
		return $entity;
	}

}
