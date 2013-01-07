<?php
namespace Tests\Translator;

use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class TranslatorTest extends \Tests\TestCase
{

	/**
	 * @var \Extras\Translator
	 */
	protected $translator;

	public $phraseDecoratorFactory;

	/**
	 * @var \Entity\Language
	 */
	public $language;

	/**
	 * @var \Entity\Phrase\Type
	 */
	public $phraseType;


	protected function setUp() {
		$this->phraseDecoratorFactory = $this->getContext()->phraseDecoratorFactory;
		$this->phraseType = $this->getContext()->phraseTypeRepositoryAccessor->get()->findOneBy([
			'entityName' => '\Entity\Rental\Tag',
			'entityAttribute' => 'name',
		]);

		$languageRepository = $this->getContext()->languageRepositoryAccessor->get();
		$this->language = $languageRepository->findOneByOldId(144);

		$this->translator = $this->getContext()->translatorFactory->create($this->language);
	}

	public function testName() {

		$phrase = new \Entity\Phrase\Phrase;
		$phrase->setType($this->phraseType);

		/** @var $phraseDecorator \Service\Phrase\PhraseService */
		$phraseDecorator = $this->phraseDecoratorFactory->create($phrase);
		$phraseDecorator->createTranslation($this->language);

		$translation = $phraseDecorator->getTranslation($this->language);
		$translationVariations = [
			0 => [
				0 => [
					'nominative' => '0-0-nominative',
					'locative' => '0-0-locative',
				],
				1 => [
					'nominative' => '0-1-nominative',
					'locative' => '0-1-locative',
				],
			],
			1 => [
				0 => [
					'nominative' => '1-0-nominative',
					'locative' => '1-0-locative',
				],
				1 => [
					'nominative' => '1-1-nominative',
					'locative' => '1-1-locative',
				],
			],
		];
		$translation->setVariations($translationVariations);
		$i = 1;
	}

}