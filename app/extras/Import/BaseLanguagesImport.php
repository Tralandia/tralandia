<?php

namespace Extras\Import;

abstract class BaseLanguagesImport extends BaseImport {

	public $languageOptions = array(
		'sk' => array(
			'genders' => array(
				0 => 'Neuter'
				1 => 'Masculine', 
				2 => 'Feminine', 
			),
			'primaryGender' => 1,
			'plurals' => array(
				'5plus' => array('name' => '0, 5+', 'pattern' => '$i==0 || $i>4'),
				'one' => array('name' => '1', 'pattern' => '$i==1'),
				'twofour' => array('name' => '2-4', 'pattern' => '$i>1 && $i<5'),
			),
			'primarySingular' => 'one',
			'primaryPlural' => 'twofour',
		),
		'en' => array(
			'genders' => array(),
			'primaryGender' => NULL,
			'plurals' => array(
				'rule' => '(n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, 2 or more',
				),
			),
			'primarySingular' => 0,
			'primaryPlural' => 1,
		),
		'de' => array(
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
	);

}