<?php

namespace Listener;


use Entity\Language;
use Entity\Log\History;
use Entity\User\User;

class TranslationsSetAsPaidHistoryLogListener extends BaseHistoryLogListener {

	public function getSubscribedEvents()
	{
		return ['AdminModule\Grids\Dictionary\ManagerGrid::onMarkAsPaid'];
	}

	public function onMarkAsPaid(Language $language, $changedIds, User $requestedBy)
	{
		$log = $this->createLog(History::TRANSLATIONS_SET_AS_PAID);

		$translator = $language->getTranslator();
		$data = [
			'language' => [
				'id' => $language->getId(),
				'iso' => $language->getIso(),
				'translationPrice' => $language->getTranslationPrice(),
			],
			'translator' => [
				'id' => $translator ? $translator->getId() : NULL,
				'email' => $translator ? $translator->getEmail() : NULL,
			],
			'requestedBy' => [
				'id' => $requestedBy->getId(),
				'email' => $requestedBy->getEmail(),
			],
			'changedIds' => $changedIds,
		];

		$log->setData($data);

		$this->em->persist($log);
		$this->em->flush($log);
	}


}
