<?php
namespace Repository\Phrase;

use Doctrine\ORM\Query\Expr;

/**
 * TranslationRepository class
 *
 * @author Dávid Ďurika
 */
class TranslationRepository extends \Repository\BaseRepository {

	protected $centralLanguage;

	public function inject($centralLanguage) {
		list($this->centralLanguage) = func_get_args();
	}

	public function toTranslate() {
		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm->addEntityResult('\Entity\Phrase\Translation', 't');
		$rsm->addFieldResult('t', 'id', 'id');
		$rsm->addMetaResult('t', 'language_id', 'language_id');

		$query = 'select t.id, t.language_id
				FROM phrase_translation AS t
				JOIN phrase_translation AS s ON t.phrase_id = s.phrase_id AND s.language_id = '.$this->centralLanguage.'
				WHERE s.timeTranslated > t.timeTranslated';
		$query = $this->_em->createNativeQuery($query, $rsm);


		return $query->getArrayResult();
	}

}
