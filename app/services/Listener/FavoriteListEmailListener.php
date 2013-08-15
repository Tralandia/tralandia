<?php
namespace Listener;

use Entity\FavoriteList;
use Entity\User\RentalReservation;
use Entity\User\User;
use Nette;

class FavoriteListEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['FrontModule\RentalListPresenter::onSendFavoriteList'];
	}

	public function onSendFavoriteList(FavoriteList $favoriteList, User $receiver)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($favoriteList, $receiver);
		$body = $emailCompiler->compileBody();

		$message->setHtmlBody($body);
		$message->addTo($receiver->getLogin());

		$this->mailer->send($message);
	}


	public function prepareCompiler($favoriteList, $receiver)
	{

		$emailCompiler = $this->createCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('reservation-form'));

		return $emailCompiler;
	}

}
