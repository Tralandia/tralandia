<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;
use Environment\Collator;
use Nette\Localization\ITranslator;

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

	public function getSupportedSortedByName() {
		$supporded = $this->findSupported();

		$sort = array();
		foreach ($supporded as $key => $language) {
			$sort[$language->name->getTranslation($language)->getDefaultVariation()] = $key;
		}

		// TODO: Zoradit pomocou \Collator
		ksort($sort);

		$return = array();
		foreach ($sort as $name => $key) {
			$return[] = $supporded[$key];
		}

		return $return;

	}


}