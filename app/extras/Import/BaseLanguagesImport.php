<?php

namespace Extras\Import;

abstract class BaseLanguagesImport extends BaseImport {

	public $languageOptions = array(
		'sk' => array(
			'genders' => array('feminine' => 'Feminine', 'masculine' => 'Masculine', 'neuter' => 'Neuter'),
			'primaryGender' => 'masculine',
			'plurals' => array(
				'5plus' => array('name' => '0, 5+', 'pattern' => '$i==0 || $i>4'),
				'one' => array('name' => '1', 'pattern' => '$i==1'),
				'twofour' => array('name' => '2-4', 'pattern' => '$i>1 && $i<5'),
			),
			'primarySingular' => 'one',
			'primaryPlural' => 'twofour',
		),
		// 'en' => array(...),
	);

}