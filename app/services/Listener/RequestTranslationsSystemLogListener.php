<?php

namespace Listener;


use Entity\Language;
use Entity\Log\System;
use Entity\User\User;

class RequestTranslationsSystemLogListener extends BaseSystemLogListener {

	public function getSubscribedEvents()
	{
		return ['AdminModule\Grids\Dictionary\ManagerGrid::onRequestTranslations'];
	}

	public function onRequestTranslations(Language $language, $wordsCountToPay, User $requestedBy)
	{

		$log = $this->createLog(System::TRANSLATION_INVOICE_REQUEST);

		$this->em->persist($log);
		$this->em->flush($log);
	}


}
