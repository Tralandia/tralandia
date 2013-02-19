<?php
namespace Service\Phrase;

use Nette;
use Nette\Utils\Strings;
use Repository\Phrase\PhraseRepository;
use Repository\LanguageRepository;

/**
 * CreatePhrase class
 *
 * @author Dávid Ďurika
 */
class PhraseCreator extends Nette\Object
{

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */
	protected $phraseRepository;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepository;

	/**
	 * @param \Repository\Phrase\PhraseRepository $phraseRepository
	 * @param \Repository\LanguageRepository $languageRepository
	 */
	public function __construct(PhraseRepository $phraseRepository, LanguageRepository $languageRepository)
	{
		$this->phraseRepository = $phraseRepository;
		$this->languageRepository = $languageRepository;
	}

	/**
	 * @param $phraseType
	 *
	 * @return \Entity\Phrase\Phrase
	 */
	public function create($phraseType)
	{
		$phraseType = $this->findPhraseType($phraseType);

		/** @var $phrase \Entity\Phrase\Phrase */
		$phrase = $this->phraseRepository->createNew();
		$phrase->setType($phraseType);

		if($phraseType->getTranslateTo() == \Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED) {
			$supportedLanguages = $this->languageRepository->findSupported();
			foreach($supportedLanguages as $language) {
				$phrase->createTranslation($language);
			}
		}

		return $phrase;
	}

	/**
	 * @param $phraseType
	 *
	 * @return \Entity\Phrase\Type
	 * @throws \Nette\InvalidArgumentException
	 */
	private function findPhraseType($phraseType)
	{
		if(is_scalar($phraseType)) {
			if($phraseType == 'Html') {
				$entityAttribute = $entityName = 'Html';
			} else if(Strings::startsWith($phraseType, '\Entity\\')) {
				list($entityName, $entityAttribute) = explode(':', $phraseType);
			} else {
				throw new \Nette\InvalidArgumentException();
			}
			$phraseTypeRepository = $this->phraseRepository->related('type');
			$phraseType = $phraseTypeRepository->findOneBy(['entityName' => $entityName, 'entityAttribute' => $entityAttribute]);
		}

		if(!$phraseType instanceof \Entity\Phrase\Type) {
			throw new \Nette\InvalidArgumentException('Neexistujuci Phrase Type v DB.');
		}

		return $phraseType;
	}
}