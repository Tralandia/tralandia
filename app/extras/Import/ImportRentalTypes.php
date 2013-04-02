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
			$rentalType = $this->context->rentalTypeEntityFactory->create();
			$rentalType->name = $this->createPhraseFromString('\Rental\Type', 'name', 'ACTIVE', $value[0], 'en');
			$rentalType->slug = $value[0];

			// classification
			if (in_array($rentalType->slug, $this->haveClassification)) {
				$rentalType->classification = 1;
			}

			$variations = array();
			$enTranslation = $rentalType->name->createTranslation($en);
			$variations[0][0]['nominative'] = $value[0];
			$variations[1][0]['nominative'] = $value[1];
			$enTranslation->updateVariations($variations);

			$variations = array();
			$skTranslation = $rentalType->name->createTranslation($sk);
			$variations[0][0]['nominative'] = $value[2];
			$variations[1][0]['nominative'] = $value[3];
			$skTranslation->updateVariations($variations);

			$this->model->persist($rentalType);
		}
		$this->model->flush();

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
				$this->rentalPairing[$x['id']] = $this->rentalTypes[$newTypeId][0];
			}
		}

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
			if (in_array($oldId, $value[4])) return $key;
		}
		return NULL;
	}

	public function saveRentalPairing() {
		qNew('update __importVariables set value ="'.mysql_real_escape_string(\Nette\Utils\Json::encode($this->rentalPairing)).'"  where id = 2');
	}

}