<?php

namespace Services\Dictionary;


class DictionaryService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';

	const SOURCE_LANGUAGE = 1;

	public function toTranslate() {

		$qb = $this->em->createQueryBuilder();
		$qb->select('d, t')
			->from('\Entities\Dictionary\Phrase', "d")
			->leftJoin('d.translations', "t");
		$data = $qb->getQuery()->getResult();

		foreach ($data as $phrase) {
			foreach ($phrase->translations as $translation) {
				debug($translation);
			}
		}

	}

	public function getSourceLanguage() {
		return new LanguageService(self::SOURCE_LANGUAGE);
	}
	
}
