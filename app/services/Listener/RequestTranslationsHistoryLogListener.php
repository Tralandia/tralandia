<?php

namespace Listener;


use Entity\Language;
use Entity\Log\History;
use Entity\User\User;

class RequestTranslationsHistoryLogListener extends BaseHistoryLogListener {

	public function getSubscribedEvents()
	{
		return ['AdminModule\Grids\Dictionary\ManagerGrid::onRequestTranslations'];
	}

	public function onRequestTranslations(Language $language, $wordsCountToPay, User $requestedBy)
	{
		$log = $this->createLog(History::TRANSLATION_INVOICE_REQUEST);

		$translator = $language->getTranslator();
		$data = [
			'language' => [
				'id' => $language->getId(),
				'iso' => $language->getIso(),
				'translationPrice' => $language->getTranslationPrice(),
			],
			'translator' => [
				'id' => $translator->getId(),
				'email' => $translator->getEmail(),
			],
			'requestedBy' => [
				'id' => $requestedBy->getId(),
				'email' => $requestedBy->getEmail(),
			],
			'wordsCountToPay' => $wordsCountToPay,
			'totalPrice' => $language->getTranslationPrice() * $wordsCountToPay,
		];

		$log->setData($data);

		$this->em->persist($log);
		$this->em->flush($log);
	}


}
