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

	public function findByLocationTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('other', $translator, $collator);
	}

	public function findByBoardTypeForSelect(ITranslator $translator, Collator $collator)
	{
		return $this->findByTypeForSelect('board', $translator, $collator);
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
		foreach($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		return $return;
	}

	public function findImportantForSelect(ITranslator $translator, Collator $collator) {
		$rows = $this->findByImportant(TRUE);
		foreach($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		return $return;
	}

}