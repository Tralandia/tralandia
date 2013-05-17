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

		$dictionaryType = $this->createPhraseType('Latte', 'Latte', 'MARKETING');
		$dictionaryTypeMulti = $this->createPhraseType('LatteMulti', 'LatteMulti', 'MARKETING');
		$dictionaryTypeHtml = $this->createPhraseType('LatteHtml', 'LatteHtml', 'MARKETING');

		$r = q('select * from dictionary where text_type = 2');

		$i = 0;
		while ($x = mysql_fetch_array($r)) {
			if (in_array($x['id'], $this->multiIds)) {
				$newPhrase = $this->createNewPhrase($dictionaryTypeMulti, $x['id']);
			} else if ($x['is_html'] == 1) {
				$newPhrase = $this->createNewPhrase($dictionaryTypeHtml, $x['id']);
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
		$phraseTypeRepository = $this->context->phraseTypeRepositoryAccessor->get();

		$en = $languageRepository->findOneByIso('en');
		$sk = $languageRepository->findOneByIso('sk');
		$phraseType = $this->context->phraseTypeRepositoryAccessor->get()->findOneByEntityAttribute('Html');
		$phraseTypeMulti = $this->context->phraseTypeRepositoryAccessor->get()->findOneByEntityAttribute('HtmlMulti');

		$phraseCreator = new \Service\Phrase\PhraseCreator($phraseRepository, $languageRepository);

		$r = qNew('select * from __importPhrases order by id');
		while ($x = mysql_fetch_array($r)) {
			$thisPhrase = $this->context->phraseRepositoryAccessor->get()->findOneByOldId($x['id']);
			$thisType = $x['isMulti'] ? $phraseTypeMulti : $phraseType;
			if (!$thisPhrase) {
				$thisPhrase = $phraseCreator->create($thisType);
			} else {
				$thisPhrase->type = $x['isMulti'] ? $phraseTypeMulti : $phraseType;
			}
			$thisPhrase->oldId = $x['id'];
			$thisPhrase->checked = FALSE;

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
			$languageRepository->persist($thisPhrase);
		}
		$languageRepository->flush();
	}
}
