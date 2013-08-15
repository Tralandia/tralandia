<?php
namespace Service\Phrase;

use Doctrine\ORM\EntityManager;
use Extras\Models\Entity\Entity;
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
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$phraseRepository = $em->getRepository(PHRASE_ENTITY);
		$this->phraseRepository = $phraseRepository;
		$this->languageRepository = $languageRepository;
	}


	/**
	 * $phraseType example \Entity\Rental\Amenity:name
	 * @param $phraseType \Entity\Phrase\Type|string
	 * @param null $sourceTranslation
	 *
	 * @return \Entity\Phrase\Phrase
	 */
	public function create($phraseType, $sourceTranslation = NULL)
	{
		$phraseType = $this->findPhraseType($phraseType);

		/** @var $phrase \Entity\Phrase\Phrase */
		$phrase = $this->phraseRepository->createNew();
		$phrase->setType($phraseType);


		$en = $this->languageRepository->find(CENTRAL_LANGUAGE);
		$phrase->setSourceLanguage($en);
		$phrase->createTranslation($en, $sourceTranslation);

		if($phraseType->getTranslateTo() == \Entity\Phrase\Type::TRANSLATE_TO_SUPPORTED) {
			$supportedLanguages = $this->languageRepository->findSupported();
			foreach($supportedLanguages as $language) {
				if($language->getId() == $en->getId()) continue;
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
