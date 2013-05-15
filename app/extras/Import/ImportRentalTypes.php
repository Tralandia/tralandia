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

	protected $rentalTypes = array(
		array('hotel', 'hotels', 'hotel', 'hotely', array(2, 4, 412)),
		array('spa', 'spas', 'kupele', 'kupele', array(8)),
		array('villa', 'villas', 'vila', 'vily', array(17)),
		array('castle', 'castles', 'zámok', 'zámky', array(347, 348, 349)),
		array('camp', 'camps', 'kemp', 'kempy', array(7, 351)),
		array('apartment', 'apartments', 'apartmán', 'apartmány', array(1, 19)),
		array('pension', 'pensions', 'penzión', 'penzióny', array(10)),
		array('motel', 'motels', 'motel', 'motely', array(9)),
		array('hostel', 'hostels', 'hostel', 'hostely', array(3)),
		array('cottage', 'cottages', 'chata', 'chaty', array(6, 20, 21, 22, 409)),
		array('condo', 'condos', 'byt', 'byty', array(11, 15, 410)),
		array('house', 'houses', 'dom', 'domy', array(13, 14, 16, 23, 24, 358, 359, 405, 411)),
		array('farmhouse', 'farmhouses', 'farma', 'farmy', array(12, 406, 407, 408)),
		array('other', 'others', 'iné', 'iné', array(5, 350)),
	);

	protected $rentalPairing = array();

	protected $haveClassification = array('apartment', 'hotel', 'motel');

	public function doImport($subsection = NULL) {

		$context = $this->context;
		$model = $this->model;

		$sk = $context->languageRepositoryAccessor->get()->findOneByIso('sk');
		$en = $context->languageRepositoryAccessor->get()->findOneByIso('en');

		$phrase = $this->createPhraseType('\Rental\Type', 'name', 'ACTIVE');
		$questionPhraseType = $this->createPhraseType('\Rental\interviewQuestion', 'question', 'ACTIVE');

		$this->model->persist($phrase);
		$this->model->flush();

		foreach ($this->rentalTypes as $key => $value) {
			$rentalType = $this->context->rentalTypeRepositoryAccessor->get()->createNew();
			// vykomentovane, lebo EN bolo potom 2x
			//$rentalType->name = $this->createPhraseFromString('\Rental\Type', 'name', 'ACTIVE', $value[0], 'en');
			$rentalType->slug = $value[0];

			// classification
			if (in_array($rentalType->slug, $this->haveClassification)) {
				$rentalType->classification = 1;
			}

			//d($rentalType); exit;
			$variations = array();
			$enTranslation = $rentalType->name->getTranslation($en);
			$variations[0][0]['nominative'] = $value[0];
			$variations[1][0]['nominative'] = $value[1];
			$enTranslation->updateVariations($variations);

			$variations = array();
			$skTranslation = $rentalType->name->getTranslation($sk);
			$variations[0][0]['nominative'] = $value[2];
			$variations[1][0]['nominative'] = $value[3];
			$skTranslation->updateVariations($variations);

			$this->model->persist($rentalType);
		}
		$this->model->flush();

		$this->context->generatePathSegmentsRobot->runTypes();

		$this->model->flush();

		$c = 0;
		$r = q('select * from objects_types_new');
		while($x = mysql_fetch_array($r)) {
			$newTypeId = NULL;
			if ($x['language_id'] == 144) {
				$newTypeId = $this->findSkInNew($x['id']);
			} else {
				$skTypeId = array_unique(array_filter(explode(',', $x['associations'])));
				sort($skTypeId);
				if (isset($skTypeId[0])) $newTypeId = $this->findSkInNew($skTypeId[0]);
			}

			if ($newTypeId !== NULL) {
				$this->rentalPairing[$x['id']] = $this->rentalTypes[$newTypeId-1][0];

				$thisLanguage = $context->languageRepositoryAccessor->get()->findOneByOldId($x['language_id']);
				
				$newPathSegment = $context->routingPathSegmentRepositoryAccessor->get()->findOneBy(
					array(
						'type' => 8,
						'entityId' => $newTypeId,
						'language' => $thisLanguage,
					)
				);



				if (isset($newPathSegment) && is_object($newPathSegment) && $newPathSegment->pathSegment != $x['name_url']) {
					//d($x);
					//d($newTypeId);
					//d($newPathSegment);
					$oldPathSegment = $this->context->routingPathSegmentOldRepositoryAccessor->get()->createNew();
					$oldPathSegment->pathSegmentNew = $newPathSegment;
					$oldPathSegment->language = $thisLanguage;
					$oldPathSegment->type = 8;
					$oldPathSegment->entityId = $newTypeId;
					$oldPathSegment->pathSegment = $x['name_url'];
		
					$this->model->persist($oldPathSegment);
					$c++;
					//d($oldPathSegment);					
				} else {
					//d('je to rovnake: '.$x['name_url']);
				}
			}
		}

		$this->model->flush();
		d($c);


		$this->saveRentalPairing();

		// Rental Interview Questions
		$r = q('select * from interview_questions where id < 11');
		$newFeQuestions = array(
			1 => 100124,
			2 => 100125,
			3 => 100126,
			4 => 100127,
			5 => 100128,
			6 => 100129,
			7 => 100130,
			8 => 100131,
			9 => 100132,
			10 => 100133,
		);
		while ($x = mysql_fetch_array($r)) {
			$question = $this->context->rentalInterviewQuestionRepositoryAccessor->get()->createNew(FALSE);
			$question->oldId = $x['id'];
			$question->question = $this->createNewPhrase($questionPhraseType, $x['name_dic_id']);
			$question->questionFe = $context->phraseRepositoryAccessor->get()->findOneByOldId($newFeQuestions[$x['id']]);;
			$this->model->persist($question);
		}
		$this->model->flush();

	}

	protected function findSkInNew($oldId) {
		foreach ($this->rentalTypes as $key => $value) {
			if (in_array($oldId, $value[4])) return ($key+1);
		}
		return NULL;
	}

	public function saveRentalPairing() {
		qNew('update __importVariables set value ="'.mysql_real_escape_string(\Nette\Utils\Json::encode($this->rentalPairing)).'"  where id = 2');
	}

}