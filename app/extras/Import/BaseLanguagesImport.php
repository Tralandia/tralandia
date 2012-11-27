<?php

namespace Extras\Import;

abstract class BaseLanguagesImport extends BaseImport {

	public $languageOptions = array(
		'en' => array(
			'genders' => array(),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'sk' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n==1) ? 0 : ($n>=2 && $n<=4) ? 1 : 2',
				'names' => array(
					0 => 'Singular',
					1 => '2, 3, 4',
					2 => 'Zero, 5 or more',
				),
			),
		),
		'de' => array(
			'genders' => array(
				0 => 'Masculine DER', 
				1 => 'Feminine DIE', 
				2 => 'Neuter DAS'
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'hr' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n%10==1 && $n%100!=11 ? 0 : $n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2)',
				'names' => array(
					0 => 'Singular',
					1 => '2, 3, 4, 12, 13, 14, ...',
					2 => 'Zero, 5 or more',
				),
			),
		),
		'it' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'pl' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n==1 ? 0 : $n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2)',
				'names' => array(
					0 => 'Singular',
					1 => '2, 3, 4, 12, 13, 14, ...',
					2 => 'Zero, 5 or more',
				),
			),
		),
		'ru' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n%10==1 && $n%100!=11 ? 0 : $n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2)',
				'names' => array(
					0 => 'Singular',
					1 => '2, 3, 4, 12, 13, 14, ...',
					2 => 'Zero, 5 or more',
				),
			),
		),
		'cs' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n==1) ? 0 : ($n>=2 && $n<=4) ? 1 : 2',
				'names' => array(
					0 => 'Singular',
					1 => '2, 3, 4',
					2 => 'Zero, 5 or more',
				),
			),
		),
		'el' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'es' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'fr' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'hu' => array(
			'genders' => array(),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'nl' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
				2 => 'Neuter'
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
		'pt' => array(
			'genders' => array(
				0 => 'Masculine', 
				1 => 'Feminine', 
			),
			'plurals' => array(
				'rule' => '($n != 1)',
				'names' => array(
					0 => 'Singular',
					1 => 'Zero, Plural',
				),
			),
		),
	);

}