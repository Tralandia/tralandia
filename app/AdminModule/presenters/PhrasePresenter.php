<?php

namespace AdminModule;


use Entity\User\Role;
use Nette\Application\BadRequestException;

class PhrasePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Service\Phrase\PhraseCreator
	 */
	protected $phraseCreator;

	protected $phrase;


	public function actionAdd($type)
	{
		$phraseRepository = $this->em->getRepository(PHRASE_ENTITY);
		$phraseTypeRepository = $this->em->getRepository(PHRASE_TYPE_ENTITY);
		$type = $phraseTypeRepository->findOneBy(['entityName' => $type]);
		if(!$type) {
			throw new BadRequestException;
		}

		$phrase = $this->phraseCreator->create($type);
		$phraseRepository->save($phrase);
		$this->redirect('PhraseList:searchId', ['id' => $phrase->getId()]);
	}

}
