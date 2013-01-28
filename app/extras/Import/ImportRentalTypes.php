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

	protected $skPlurals = array(
		'apartment' => 'apartmány',
		'mountain hotel' => 'horské hotely',
		'hostel' => 'hostely',
		'hotel' => 'hotely',
		'houseboat' => 'hausbóty',
		'chalet' => 'chaty',
		'campsite' => 'kempingy',
		'spas' => 'kúpele',
		'motel' => 'motely',
		'guesthouse' => 'ubytovne',
		'private lodging' => 'priváty',
		'ranch' => 'ranče',
		'recreational home' => 'ubytovne',
		'mansion' => '',
		'studio' => 'štúdiá',
		'dormitory' => 'internáty',
		'villa' => 'vily',
		'apartment house' => 'apartmánové domy',
		'cottage' => 'chaty',
		'timbered house' => 'drevenice',
		'cabin' => 'chaty',
		'holiday home' => 'výletné domy',
		'tourist dormitory' => '',
		'boat' => 'lode',
		'barn' => 'stodoly',
		'bungalow' => 'bungalovy',
		'castle' => 'zámky',
		'condo' => '',
		'estate' => '',
		'farmhouse' => 'farmy',
		'house' => 'domy',
		'townhome' => 'mestské domy',
		'yacht' => 'jachty',
		'B&B' => '',
		'caravan' => 'karavany',
		'cave house' => 'jaskynné domy',
		'chateau' => 'chaty',
		'manor' => '',
		'log cabin' => 'drevenice',
	);

	public function doImport($subsection = NULL) {

		$phrase = $this->createPhraseType('\Rental\Type', 'name', 'ACTIVE');
		$questionPhraseType = $this->createPhraseType('\Rental\interviewQuestion', 'question', 'ACTIVE');
		$this->model->persist($phrase);
		$this->model->flush();

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

		$sk = $this->context->languageRepositoryAccessor->get()->findOneByIso('sk');

		$r = q('select * from objects_types_new where language_id = 38');
		while($x = mysql_fetch_array($r)) {
			$rentalType = $this->context->rentalTypeEntityFactory->create();
			$rentalType->name = $this->createPhraseFromString('\Rental\Type', 'name', 'ACTIVE', $x['name'], 'en');
			
			if (strlen($this->skPlurals[$x['name']]) != 0) {
				$skTranslation = $rentalType->name->createTranslation($sk);
				$variations[1][0]['nominative'] = $this->skPlurals[$x['name']];
				$skTranslation->updateVariations($variations);
			}
			$rentalType->oldId = $x['id'];
			$this->model->persist($rentalType);
		}
		$this->model->flush();

		$r = q('select * from objects_types_new where language_id <> 38 && trax_en_type_id > 0');
		while($x = mysql_fetch_array($r)) {
			$rentalType = $this->context->rentalTypeRepositoryAccessor->get()->findOneByOldId($x['trax_en_type_id']);
			
			if (!$rentalType) throw new \Nette\UnexpectedValueException('nenasiel som EN rental Type oldID: '.$x['trax_en_type_id']); 
			
			$thisLanguage = $this->context->languageRepositoryAccessor->get()->findOneByOldId($x['language_id']);
			if (!$thisLanguage) continue;

			$thisPhrase = $this->context->phraseDecoratorFactory->create($rentalType->name);
			if (!$thisPhrase->hasTranslation($thisLanguage)) {
				$thisTranslation = $thisPhrase->createTranslation($thisLanguage, $x['name']);
			}
			$this->model->persist($rentalType);
		}
		$this->model->flush();

		// Rental Interview Questions
		$r = q('select * from interview_questions');
		while ($x = mysql_fetch_array($r)) {
			$question = $this->context->rentalInterviewQuestionRepositoryAccessor->get()->createNew(FALSE);
			$question->oldId = $x['id'];
			$question->question = $this->createNewPhrase($questionPhraseType, $x['name_dic_id']);
			$this->model->persist($question);
		}
		$this->model->flush();

	}

}