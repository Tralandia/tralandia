<?php
namespace Listener;

use Entity\Ticket\Message;
use Entity\Ticket\Ticket;
use Entity\User\RentalReservation;
use Nette;

class NewTicketEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['FrontModule\Forms\TicketForm::onSuccess'];
	}

	public function onSuccess(Message $ticketMessage)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($ticketMessage);
		$body = $emailCompiler->compileBody();

		$message->setHtmlBody($body);
		$message->addTo($ticketMessage->getFrom()->getLogin());

		$this->mailer->send($message);
	}

	/**
	 * @param \Entity\Ticket\Message $ticketMessage
	 *
	 * @return \Mail\Compiler
	 */
	public function prepareCompiler(Message $ticketMessage)
	{
		$receiver = $ticketMessage->getFrom();

		$emailCompiler = $this->getCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('reservation-form'));
		$emailCompiler->setLayout($this->getLayout());
		$emailCompiler->addTicket('ticket', $ticketMessage->getTicket());

		return $emailCompiler;
	}

}
