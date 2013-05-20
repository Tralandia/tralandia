<?php

namespace AdminModule;


use Nette\Application\BadRequestException;

class DictionaryManagerPresenter extends AdminPresenter {

	/**
	 * @var array
	 */
	public $onRequestTranslations = [];

	public function actionRequestTranslations($id)
	{
		$language = $this->languageRepositoryAccessor->get()->find($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$this->onRequestTranslations($language);
	}


}
