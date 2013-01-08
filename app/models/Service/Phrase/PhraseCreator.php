<?php
namespace Serivice\Phrase;

use Nette;
use Nette\Utils\Strings;
use Repository\Phrase\PhraseRepository;

/**
 * CreatePhrase class
 *
 * @author Dávid Ďurika
 */
class PhraseCreator extends Nette\Object
{

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */protected $phraseRepository;

	/**
	 * @param \Repository\Phrase\PhraseRepository $phraseRepository
	 */
	public function __construct(PhraseRepository $phraseRepository)
	{
		$this->phraseRepository = $phraseRepository;
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
			throw new \Nette\InvalidArgumentException();
		}

		return $phraseType;
	}
}