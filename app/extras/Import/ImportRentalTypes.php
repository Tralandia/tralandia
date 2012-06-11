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

class ImportRentalTypes extends BaseImport {

	public function doImport($subsection = NULL) {

		$import = new \Extras\Import\BaseImport();
		$import->undoSection('rentalTypes');

		$this->createDictionaryType('\Rental\Type', 'name', 'ACTIVE');
		\Extras\Models\Service::flush(FALSE);

		$r = qf('select * from objects_types_new limit 1');
		if (!isset($r['trax_en_type_id'])) {
			q('ALTER TABLE `objects_types_new` ADD `trax_en_type_id` INT(10)  UNSIGNED  NULL  DEFAULT NULL  AFTER `ppc_enabled`');
			q('ALTER TABLE `objects_types_new` ADD INDEX (`trax_en_type_id`)');
		}

		q('update objects_types_new set trax_en_type_id = 0');

		$r = q('select * from objects_types_new');
		while($x = mysql_fetch_array($r)) {
			$enTypeId = 0;
			if ($x['language_id'] == 144) {
				$enTypeId = qc('select id from objects_types_new where language_id = 38 and associations like "%,'.$x['id'].',%" order by id asc');
			} else if ($x['language_id'] == 38) {
				$enTypeId = $x['id'];
			} else {
				$skTypeId = array_unique(array_filter(explode(',', $x['associations'])));
				sort($skTypeId);
				if (is_array($skTypeId) && count($skTypeId)) {
					$skTypeId = $skTypeId[0];
					$enTypeId = qc('select id from objects_types_new where language_id = 38 and associations like "%,'.$skTypeId.',%" order by id asc');
				}
			}
			q('update objects_types_new set trax_en_type_id = '.$enTypeId.' where id = '.$x['id']);
		}

		$r = q('select * from objects_types_new where language_id = 38');
		while($x = mysql_fetch_array($r)) {
			$rentalType = \Service\Rental\Type::get();
			$rentalType->name = $this->createPhraseFromString('\Rental\Type', 'name', 'ACTIVE', $x['name'], \Service\Dictionary\Language::getByOldId(38));
			$rentalType->oldId = $x['id'];
			$thisPhrase = \Service\Dictionary\Phrase::get($rentalType->name);
			$rentalType->save();

		}
		\Extras\Models\Service::flush(FALSE);

		$r = q('select * from objects_types_new where language_id <> 38 && trax_en_type_id > 0');
		while($x = mysql_fetch_array($r)) {
			$rentalType = \Service\Rental\Type::getByOldId($x['trax_en_type_id']);
			if (!$rentalType) throw new \Nette\UnexpectedValueException('nenasiel som EN rental Type oldID: '.$x['trax_en_type_id']); 
			$thisLanguage = \Service\Dictionary\Language::getByOldId($x['language_id']);
			if (!$thisLanguage) continue;
			$thisPhrase = \Service\Dictionary\Phrase::get($rentalType->name);
			if (!$thisPhrase->hasTranslation($thisLanguage)) {
				$thisTranslation = $this->createTranslation($thisLanguage, $x['name']);
				$thisPhrase->addTranslation($thisTranslation);
			}
			$rentalType->save();
		}

		$this->savedVariables['importedSections']['rentalTypes'] = 1;

	}

}