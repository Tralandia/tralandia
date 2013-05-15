<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;
use Environment\Collator;
use Nette\Localization\ITranslator;

/**
 * CurrencyRepository class
 *
 * @author Dávid Ďurika
 */
class CurrencyRepository extends \Repository\BaseRepository {

	public function getForSelect(ITranslator $translator, Collator $collator)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')->from($this->_entityName, 'e');

		$rows = $qb->getQuery()->getResult();
		$return = [];
		foreach ($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}

		sort($return);

		return $return;
	}

}
