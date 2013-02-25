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

class ImportHtmlPhrases extends BaseImport {

	protected $multiIds = array(12277, 2443, 940, 925);

	public function doImport($subsection = NULL) {
		$this->{$subsection}();
	}
	public function importPhrases() {

		$dictionaryType = $this->createPhraseType('Html', 'Html', 'MARKETING');
		$dictionaryTypeMulti = $this->createPhraseType('HtmlMulti', 'HtmlMulti', 'MARKETING');

		if ($this->developmentMode == TRUE) {
			$r = q('select * from dictionary where text_type = 2');
		} else {
			$r = q('select * from dictionary where text_type = 2');
		}
		$i = 0;
		while ($x = mysql_fetch_array($r)) {
			if (in_array($x['id'], $this->multiIds)) {
				$newPhrase = $this->createNewPhrase($dictionaryTypeMulti, $x['id']);
			} else {
				$newPhrase = $this->createNewPhrase($dictionaryType, $x['id']);
			}

			$details = array(
				'translationHelp' => $x['help'],
			);
			$newPhrase->details = $details;
			$this->context->model->persist($newPhrase);
			$i++;
		}
		$this->context->model->flush();

		$this->savedVariables['importedSections']['htmlPhrases'] = 1;
	}

	public function importNewPhrases() {
		$languageRepository = $this->context->languageRepositoryAccessor->get();
		$phraseRepository = $this->context->phraseRepositoryAccessor->get();

		$en = $languageRepository->findOneByIso('en');
		$sk = $languageRepository->findOneByIso('sk');
		$phraseType = $this->context->phraseTypeRepositoryAccessor->get()->findOneByEntityAttribute('Html');
		$phraseTypeMulti = $this->context->phraseTypeRepositoryAccessor->get()->findOneByEntityAttribute('HtmlMulti');

		$phraseCreator = new \Service\Phrase\PhraseCreator($phraseRepository, $languageRepository);

		$r = qNew('select * from __importPhrases order by id');
		while ($x = mysql_fetch_array($r)) {
			$thisPhrase = $this->context->phraseRepositoryAccessor->get()->findOneByOldId($x['id']);
			if (!$thisPhrase) {
				$thisType = $x['isMulti'] ? $phraseTypeMulti : $phraseType;
				//d($thisType);
				$thisPhrase = $phraseCreator->create($thisType);
			}
			$thisPhrase->oldId = $x['id'];

			$thisPhrase->setTranslationText($en, $x['en0']);
			$thisPhrase->setTranslationText($sk, $x['sk0']);
			if ($x['isMulti']) {
				$variations = array();
				$variations[0][0]['nominative'] = $x['en0'];
				$variations[1][0]['nominative'] = $x['en1'];
				$thisPhrase->getTranslation($en)->setVariations($variations);

				$variations = array();
				$variations[0][0]['nominative'] = $x['sk0'];
				$variations[1][0]['nominative'] = $x['sk1'];
				$variations[2][0]['nominative'] = $x['sk2'];
				$thisPhrase->getTranslation($sk)->setVariations($variations);
			}
			d($thisPhrase->getTranslation($sk)->translation);
			$languageRepository->persist($thisPhrase); 
		}
		$languageRepository->flush();
	}
}