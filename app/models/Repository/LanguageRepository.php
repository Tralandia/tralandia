<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class LanguageRepository extends \Repository\BaseRepository {

	public function findSupported() {
		$entityName = $this->_entityName;
		return $this->findBySupported($entityName::SUPPORTED);
	}

	public function getForSelect()
	{
		$return = [];
		$rows = $this->findAll();
		foreach($rows as $row) {
			$return[$row->id] = $row->name->id;
		}

		return $return;
	}

}