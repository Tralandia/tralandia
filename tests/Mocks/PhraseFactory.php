<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/04/14 10:37
 */

namespace Tests\Mocks;


use Entity\Language;
use Nette;

class PhraseFactory
{

	public static function create(array $translations = NULL, Language $sourceLanguage = null)
	{
		$phrase = \Mockery::mock('\Entity\Phrase\Phrase');

		$phrase->byDefault()->shouldReceive('getSourceTranslationText')->andReturn('source translation text');
		$phrase->byDefault()->shouldReceive('getCentralTranslationText')->andReturn('central translation text');

//		foreach($translations as $language => $variations) {
//			$translation = TranslationFactory::create($variations);
//
//			$phrase->byDefault()->shouldReceive()->zeroOrMoreTimes();
//		}

		return $phrase;
	}

}
