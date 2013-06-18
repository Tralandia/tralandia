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

	/**
	 * @autowire
	 * @var \Service\Phrase\PhraseRemover
	 */
	protected $phraseRemover;

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


	public function actionDelete($id)
	{
		$phrase = $this->findPhrase($id);
		$this->phraseRemover->remove($phrase);
		$this->payload->success = true;
		$this->sendPayload();
	}

}
