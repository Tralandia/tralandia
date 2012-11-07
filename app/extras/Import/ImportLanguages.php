<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class ImportLanguages extends BaseLanguagesImport {

	public function doImport($subsection = NULL) {

		$this->undoSection('languages');

		$r = q('select * from languages order by id');

		$nameDicIds = array();
		$defaulOptions = array(
			'genders' => array('feminine' => 'Feminine', 'masculine' => 'Masculine', 'neuter' => 'Neuter'),
			'primaryGender' => 'masculine',
			'plurals' => array(
				'5plus' => array('name' => '0, 5+', 'pattern' => '$i==0 || $i>4'),
				'one' => array('name' => '1', 'pattern' => '$i==1'),
				'twofour' => array('name' => '2-4', 'pattern' => '$i>1 && $i<5'),
			),
			'primarySingular' => 'one',
			'primaryPlural' => 'twofour',
		);
		while($x = mysql_fetch_array($r)) {
			$e = $this->context->languageEntityFactory->create();

			$e->oldId = $x['id'];
			$e->iso = $x['iso'];
			$e->supported = (bool)$x['translated'];
			$e->defaultCollation = $x['default_collation'];
			$e->details = explode2Levels(';', ':', $x['attributes']);
			
			if(array_key_exists($x['iso'], $this->languageOptions)) {
				$options = $this->languageOptions[$x['iso']];
			} else {
				$options = $defaulOptions;
			}

			foreach ($options as $key => $value) {
				$e->{$key} = $value;
			}
			$this->model->persist($e);

			$nameDicIds[$x['id']] = $x['name_dic_id'];
		}
		$this->model->flush();

		$phraseType = $this->createPhraseType('\Language', 'name', 'ACTIVE');
		$allLanguages = $this->context->languageRepository->findAll();
		foreach ($allLanguages as $language) {
			$language->name = $this->createNewPhrase($phraseType, $nameDicIds[$language->oldId]);
		}
		$this->model->flush();

		$this->savedVariables['importedSections']['languages'] = 1;
	}

}