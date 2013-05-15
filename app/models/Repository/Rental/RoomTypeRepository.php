<?php
namespace Repository\Rental;

use Environment\Collator;
use Nette\Localization\ITranslator;

/**
 * AddressRepository class
 *
 * @author Dávid Ďurika
 */
class RoomTypeRepository extends \Repository\BaseRepository
{

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 * @param \Environment\Collator $collator
	 *
	 * @return array
	 */
	public function getForSelect(ITranslator $translator, Collator $collator)
	{
		$return = [];
		$rows = $this->findAll();
		foreach($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		foreach($rows as $row) {
			$return[$row->id] = [
				'label' => $return[$row->id],
				'entity' => $row,
			];
		}

		return $return;
	}

}
