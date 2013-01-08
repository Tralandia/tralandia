<?php
namespace Serivice\Phrase;

use Nette;
use Nette\Utils\Strings;
use Model\Phrase\IPhraseDecoratorFactory;
use Repository\Phrase\PhraseRepository;

/**
 * CreatePhrase class
 *
 * @author Dávid Ďurika
 */
class CreatePhrase extends Nette\Object
{
	/**
	 * @var \Model\Phrase\IPhraseDecoratorFactory
	 */
	protected $phraseDecoratorFactory;

	/**
	 * @var \Repository\Phrase\PhraseRepository
	 */protected $phraseRepository;

	/**
	 * @param \Model\Phrase\IPhraseDecoratorFactory $phraseDecoratorFactory
	 * @param \Repository\Phrase\PhraseRepository $phraseRepository
	 */
	public function __construct(IPhraseDecoratorFactory $phraseDecoratorFactory, PhraseRepository $phraseRepository)
	{
		$this->phraseDecoratorFactory = $phraseDecoratorFactory;
		$this->phraseRepository = $phraseRepository;
	}

	/**
	 * @param $phraseType
	 *
	 * @return \Service\Phrase\PhraseService
	 */
	public function create($phraseType)
	{
		$phraseType = $this->findPhraseType($phraseType);

		/** @var $phrase \Entity\Phrase\Phrase */
		$phrase = $this->phraseRepository->createNew();
		$phrase->setType($phraseType);

		$phraseDecorator = $this->phraseDecoratorFactory->create($phrase);

		return $phraseDecorator;
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
			}
			$phraseTypeRepository = $this->phraseRepository->related('type');
			$phraseType = $phraseTypeRepository->findOneBy(['entityName' => $entityName, 'entityAttribute' => $entityAttribute]);
		}

		if(!$phraseType instanceof \Entity\Phrase\Type) {
			throw Nette\InvalidArgumentException();
		}

		return $phraseType;
	}
}