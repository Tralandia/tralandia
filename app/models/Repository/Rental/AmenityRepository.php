<?php
namespace Repository\Rental;

use Doctrine\ORM\Query\Expr;
use Environment\Collator;
use Nette\Localization\ITranslator;

/**
 * AddressRepository class
 *
 * @author Dávid Ďurika
 */
class AmenityRepository extends \Repository\BaseRepository
{

	public function findByAnimalTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('animal', $translator);
	}


	/**
	 * @param $type
	 * @param ITranslator $translator
	 *
	 * @return array
	 */
	protected function findByTypeForSortBySortingSelect($type, ITranslator $translator)
	{
		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', $type)
			->orderBy('e.sorting', 'ASC');

		$rows = $qb->getQuery()->getResult();
		$return = [];
		foreach ($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}

		return $return;
	}

	/**
	 * @param $type
	 * @param ITranslator $translator
	 *
	 * @return array
	 */
	protected function findByType($type)
	{
		$qb = $this->createQueryBuilder();
		if ($type instanceof \Entity\Rental\AmenityType) {
			$qb->andWhere($qb->expr()->eq('e.type', ':type'))->setParameter('type', $type);
		} else {
			$qb->leftJoin('e.type', 't')
				->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', $type);
		}

		$qb->orderBy('e.sorting', 'ASC');

		return $qb->getQuery()->getResult();
	}

	public function findBySeparateGroupsType()
	{
		return $this->findByType('separate-groups');
	}

	public function findByPetType()
	{
		return $this->findByType('animal');
	}


	public function findByOwnerAvailabilityType()
	{
		return $this->findByType('owner-availability');
	}


	public function findByBoardTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('board', $translator);
	}


	public function findByChildrenTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('children', $translator);
	}


	public function findByServiceTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('service', $translator);
	}


	public function findByWellnessTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('wellness', $translator);
	}


	public function findByKitchenTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('kitchen', $translator);
	}


	public function findByBathroomTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('bathroom', $translator);
	}


	public function findBySeparateGroupsTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('separate-groups', $translator);
	}


	public function findByLocationTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('location', $translator);
	}


	public function findByAvailabilityTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('owner-availability', $translator, $collator);
	}


	public function findByNearByTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('near-by', $translator, $collator);
	}


	public function findByRentalServicesTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('rental-services', $translator, $collator);
	}


	public function findByOnFacilityTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('on-facility', $translator, $collator);
	}


	public function findBySportsFunTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('sports-fun', $translator, $collator);
	}


	protected function findByTypeForSelect($type, ITranslator $translator, Collator $collator)
	{
		$type = $this->related('type')->findOneBySlug($type);
		$return = [];
		$rows = $this->findByType($type);
		foreach ($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		return $return;
	}


	public function findImportantForSelect(ITranslator $translator, Collator $collator)
	{
		$rows = $this->findByImportant(TRUE);
		foreach ($rows as $row) {
			if ($row->type->slug == 'animal') continue;
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		return $return;
	}

}
