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
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('Html', 'Html')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Currency', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Location\Location', 'name')
			->setTranslateTo('important')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Location\Location', 'nameOfficial')
			->setTranslateTo('important')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Location\Location', 'nameShort')
			->setTranslateTo('important')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Location\Type', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Company\Company', 'registrator')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Amenity', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Tag', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(1)
			->setGenderRequired(0)
			->setGenderVariationsRequired(1)
			->setLocativesRequired(1)
			->setPositionRequired(1)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\AmenityType', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Type', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(1)
			->setGenderRequired(1)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(1)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Rental\Rental', 'name')
			->setTranslateTo('none')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Rental\Rental', 'briefDescription')
			->setTranslateTo('none')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Rental\Rental', 'description')
			->setTranslateTo('none')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Rental\Rental', 'teaser')
			->setTranslateTo('none')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(1);

		$this->createPhraseType('\Entity\Invoice\UseType', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Invoice\ServiceDuration', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Invoice\ServiceType', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Invoice\Package', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Invoice\Package', 'teaser')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Attraction\Type', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(1)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Attraction\Attraction', 'name')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Attraction\Attraction', 'descrition')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Email\Template', 'subject')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Email\Template', 'body')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'title')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'heading')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'tabName')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'description')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->createPhraseType('\Entity\Seo\SeoUrl', 'ppcKeywords')
			->setTranslateTo('supported')
			->setPluralVariationsRequired(0)
			->setGenderRequired(0)
			->setGenderVariationsRequired(0)
			->setLocativesRequired(0)
			->setPositionRequired(0)
			// ->setHelpForTranslator('')
			// ->setMonthlyBudget(0)
			// ->setIldId(1)
			->setCheckingRequired(NULL);

		$this->model->flush();

	}

}