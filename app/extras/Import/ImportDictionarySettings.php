<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportDictionarySettings extends BaseImport {

	public function doImport($subsection = NULL) {

		//
		// Language ---------------------------------------
		//
		$languageRepository = $this->context->languageRepositoryAccessor->get();

		$genders = array(
			'feminine' => 'Feminine',
			'masculine' => 'Masculine',
			'neuter' => 'Neuter',
		);

		$languageRepository->findOneByIso('sk')
			->setGenders($genders)
			->setPrimaryGender('masculine')
			->setPlurals(array(
				'5plus' => array('name' => '0, 5+', 'pattern' => '$i==0 || $i>4'),
				'one' => array('name' => '1', 'pattern' => '$i==1'),
				'twofour' => array('name' => '2-4', 'pattern' => '$i>1 && $i<5'),
			))->setPrimarySingular('one')
			->setPrimaryPlural('twofour');

		// @rado tu pridaj dalsie jazyky kt. chces upravit
		

		//
		// Phrase Type ---------------------------------------
		//
		$phraseTypeRepository = $this->context->phraseTypeRepositoryAccessor->get();

		$phraseTypeRepository->findOneBy(array('entityName' => '\Entity\Location\Location', 'entityAttribute' => 'name'))
			->setName('Location name')
			->setPluralVariationsRequired(TRUE)
			->setGenderVariationsRequired(TRUE)
			->setGenderRequired(TRUE)
			->setLocativesRequired(TRUE)
			->setPositionRequired(TRUE)
			->setCheckingRequired(TRUE);

		// @rado tu pridaj dalsie jazyky kt. chces upravit

		$this->model->flush();

	}

}