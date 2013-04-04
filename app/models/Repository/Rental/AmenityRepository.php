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


	public function findByBoardTypeForSelect(ITranslator $translator)
	{
		return $this->findByTypeForSortBySortingSelect('board', $translator);
	}


	public function findByAvailabilityTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('owner-availability', $translator, $collator);
	}


	protected function findByTypeForSelect($type, ITranslator $translator, Collator $collator)
	{
		$type = $this->related('type')->findBySlug($type);
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
