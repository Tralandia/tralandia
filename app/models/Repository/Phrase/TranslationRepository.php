<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;

/**
 * TranslationRepository class
 *
 * @author Dávid Ďurika
 */
class TranslationRepository extends \Repository\BaseRepository {

	public function toTranslate() {
		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm->addEntityResult('\Entity\Phrase\Translation', 't');
		$rsm->addFieldResult('t', 'id', 'id');

		$query = 'select t.id
				FROM phrase_translation AS t
				JOIN phrase_translation AS s ON t.phrase_id = s.phrase_id AND s.language_id = '.CENTRAL_LANGUAGE.'
				WHERE s.timeTranslated > t.timeTranslated';
		$query = $this->_em->createNativeQuery($query, $rsm);


		return $query->getArrayResult();
	}

}
