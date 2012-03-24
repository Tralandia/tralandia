<?php

namespace Services\Dictionary;

use Nette\Utils\Strings,
	Entities\Dictionary as ED;

class TypeService extends \Services\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entities\Dictionary\Type';

	public function translateToLanguages() {
		$requiredLanguages = $this->requiredLanguages;
		if(!$requiredLanguages) {
			return NULL;
		} else if($requiredLanguages == ED\Type::REQUIRED_LANGUAGES_SUPPORTED) {
			$languagesList = LanguageList::getBySupported(ED\Language::SUPPORTED)->getIteratorAsServices('\Services\Dictionary\LanguageService');
		} else if($requiredLanguages == ED\Type::REQUIRED_LANGUAGES_INCOMING) {
			// @todo method or operation is not implemented
			throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		} else if(Strings::startsWith($requiredLanguages, ',,') && Strings::endsWith($requiredLanguages, ',,')) {
			$requiredLanguages = array_filter(explode(',,', $requiredLanguages));
			$languagesList = array();
			foreach ($requiredLanguages as $val) {
				$languagesList[] = LanguageService::get($val);
			}
		} else {
			// @todo method or operation is not implemented
			throw new \Nette\NotImplementedException('Requested method or operation is not implemented');
		}
		return $languagesList;
	}
	
}
