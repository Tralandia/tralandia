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
		if ($type instanceof \Entity\Rental\AmenityType) {
			return parent::findByType($type);
		}

		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from($this->_entityName, 'e')
			->leftJoin('e.type', 't')
			->andWhere($qb->expr()->eq('t.slug', ':type'))->setParameter('type', $type)
			->orderBy('e.sorting', 'ASC');

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


	public function findByBoardTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('board', $translator);
	}


	public function findByChildrenTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('children', $translator);
	}


	public function findByActivityTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('activity', $translator);
	}


	public function findByRelaxTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('relax', $translator);
	}


	public function findByServiceTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('service', $translator);
	}


	public function findByWellnessTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('wellness', $translator);
	}


	public function findByCongressTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('congress', $translator);
	}


	public function findByKitchenTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('kitchen', $translator);
	}


	public function findByBathroomTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('bathroom', $translator);
	}


	public function findByRoomTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('room', $translator);
	}


	public function findByHeatingTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('heating', $translator);
	}


	public function findByParkingTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('parking', $translator);
	}


	public function findByRoomTypeTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('room-type', $translator);
	}


	public function findBySeparateGroupsTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('separate-groups', $translator);
	}


	public function findByOtherTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('other', $translator);
	}


	public function findByLocationTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('location', $translator);
	}


	public function findByAvailabilityTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('owner-availability', $translator, $collator);
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
