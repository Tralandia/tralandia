<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/30/13 9:55 AM
 */

namespace Tralandia\Amenity;


use Environment\Collator;
use Environment\Environment;
use Nette;
use Nette\Localization\ITranslator;
use Tralandia\BaseDao;

class Amenities
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $amenityDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $amenityTypeDao;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Localization\Translator
	 */
	private $translator;

	/**
	 * @var \Environment\Collator
	 */
	private $collator;


	/**
	 * @param \Tralandia\BaseDao $amenityDao
	 * @param \Tralandia\BaseDao $amenityTypeDao
	 * @param \Environment\Environment $environment
	 */
	public function __construct(BaseDao $amenityDao, BaseDao $amenityTypeDao, Environment $environment)
	{
		$this->amenityDao = $amenityDao;
		$this->amenityTypeDao = $amenityTypeDao;
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
	}


	/**
	 * @return array
	 */
	public function findByAnimalTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('animal');
	}


	/**
	 * @param $type
	 *
	 * @return array
	 */
	protected function findByTypeSortBySortingForSelect($type)
	{
		$qb = $this->amenityDao->createQueryBuilder('e');

		$qb->innerJoin('e.type', 't')
			->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', $type)
			->orderBy('e.sorting', 'ASC');

		$rows = $qb->getQuery()->getResult();
		$return = [];
		foreach ($rows as $row) {
			$return[$row->id] = $this->translator->translate($row->name);
		}

		return $return;
	}


	/**
	 * @param $type
	 *
	 * @return \Entity\Rental\Amenity[]
	 */
	protected function findByType($type)
	{
		$qb = $this->amenityDao->createQueryBuilder('e');
		if ($type instanceof \Entity\Rental\AmenityType) {
			$qb->andWhere($qb->expr()->eq('e.type', ':type'))->setParameter('type', $type);
		} else {
			$qb->leftJoin('e.type', 't')
				->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', $type);
		}

		$qb->orderBy('e.sorting', 'ASC');

		return $qb->getQuery()->getResult();
	}


	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function findBySeparateGroupsType()
	{
		return $this->findByType('separate-groups');
	}


	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function findByPetType()
	{
		return $this->findByType('animal');
	}


	/**
	 * @return \Entity\Rental\Amenity[]
	 */
	public function findByOwnerAvailabilityType()
	{
		return $this->findByType('contact-person-availability');
	}


	public function findByBoardTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('board');
	}


	public function findByChildrenTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('children');
	}


	public function findByServiceTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('service');
	}


	public function findByWellnessTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('wellness');
	}


	public function findByKitchenTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('kitchen');
	}


	public function findByBathroomTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('bathroom');
	}


	public function findBySeparateGroupsTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('separate-groups');
	}


	public function findByLocationTypeForSelect()
	{
		return $this->findByTypeSortBySortingForSelect('location');
	}


	public function findByAvailabilityTypeForSelect()
	{
		return $this->findByTypeForSelect('contact-person-availability');
	}


	public function findByNearByTypeForSelect()
	{
		return $this->findByTypeForSelect('near-by');
	}


	public function findByRentalServicesTypeForSelect()
	{
		return $this->findByTypeForSelect('rental-services');
	}


	public function findByOnFacilityTypeForSelect()
	{
		return $this->findByTypeForSelect('on-premises');
	}


	public function findBySportsFunTypeForSelect()
	{
		return $this->findByTypeForSelect('sports-fun');
	}


	/**
	 * @param $type
	 *
	 * @return array
	 */
	protected function findByTypeForSelect($type)
	{
		$return = [];
		$rows = $this->findByType($type);
		foreach ($rows as $row) {
			$return[$row->id] = $this->translator->translate($row->name);
		}
		$this->collator->asort($return);

		return $return;
	}


	/**
	 *
	 * @return array
	 */
	public function findImportantForSelect()
	{
		$rows = $this->amenityTypeDao->findByImportant(TRUE);
		foreach ($rows as $row) {
			if ($row->type->slug == 'animal') continue;
			$return[$row->id] = $this->translator->translate($row->name);
		}
		$this->collator->asort($return);

		return $return;
	}


}
