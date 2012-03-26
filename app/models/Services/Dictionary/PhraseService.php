<?php

namespace Services\Dictionary;


class PhraseService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Phrase';


	public function getTranslation($language) {
		if($language instanceof LanguageService) {
			$language = $language->getMainEntity();
		} else if($language instanceof \Entities\Dictionary\Language) {

		} else {
			throw new \Nette\InvalidArgumentException('$language argument does not match with the expected value');
		}

		$translations = $this->translations;

		$data = NULL;
		foreach ($translations as $key => $val) {
			if($val->language == $language) {
				$data = $val;
				break;
			}
		}

/*		
		$qb = $this->getEm()->createQueryBuilder();
		$qb->select('t')
			->from('\Entities\Dictionary\Translation', 't')
			->where('t.phrase = ?1')
			->andWhere('t.language = ?2')
			->setParameters(array(
				1 => $this->getMainEntity(),
				2 => $language,
			));

		$data = $qb->getQuery()->getOneOrNullResult();
*/
		return !$data ? : TranslationService::get($data);
	}
	
}
