<?php


class TranslationTexy extends \Texy
{
	public function __construct()
	{
		parent::__construct();

		\TexyConfigurator::safeMode($this);
		\TexyConfigurator::disableImages($this);

		$this->allowed['longwords'] = FALSE;
		$this->allowed['paragraph'] = FALSE;

		$this->encoding = 'UTF-8';
		$this->allowedTags = FALSE;
		$this->mergeLines = FALSE;
		$this->tabWidth = 4;
		$this->typographyModule->locale = 'en';
		$this->headingModule->top = 1;
		$this->headingModule->balancing = \TexyHeadingModule::FIXED;
		$this->linkModule->shorten = FALSE;
	}
}
