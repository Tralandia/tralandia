<?php

namespace Extras\Import;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Extras\Models\Service,
	Service\Log as SLog;

class ImportPhraseType extends BaseImport {

	public function doImport($subsection = NULL) {

		$this->createPhraseType('\Entity\Language', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('Latte', 'Latte')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('LatteMulti', 'LatteMulti')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(1)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('LatteHtml', 'LatteHtml')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(TRUE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Currency', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Location\Location', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(FALSE)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Location\Type', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Amenity', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\AmenityType', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Type', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Rental', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_NONE)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(FALSE)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Rental\Rental', 'teaser')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_NONE)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(FALSE)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Email\Template', 'subject')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Email\Template', 'body')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'title')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'heading')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'tabName')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'description')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'ppcKeywords')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Faq\Category', 'name')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Faq\Question', 'question')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Faq\Question', 'answer')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\InterviewQuestion', 'question')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\InterviewQuestion', 'questionFe')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\InterviewAnswer', 'answer')
			->setTranslateTo(\Entity\Phrase\Type::TRANSLATE_TO_NONE)
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			->setHtml(FALSE)
			->setTranslated(TRUE)
			->setCheckingRequired(NULL);

		$this->model->flush();
	}
}