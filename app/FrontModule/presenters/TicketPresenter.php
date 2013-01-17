<?php 
namespace FrontModule;


class TicketPresenter extends BasePresenter {

	public $ticketRepositoryAccessor;
	public $ticketMessageRepositoryAccessor;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->ticketRepositoryAccessor = $dic->ticketRepositoryAccessor;
		$this->ticketMessageRepositoryAccessor = $dic->ticketMessageRepositoryAccessor;
	}

	public function renderDefault() {

		/** @var $ticket \Entity\Ticket\Ticket */
		$ticket = $this->ticketRepositoryAccessor->get()->createNew();
		$ticket->setLanguage($this->languageRepositoryAccessor->get()->find(144));

		/** @var $message \Entity\Ticket\Message */
		$message = $this->ticketMessageRepositoryAccessor->get()->createNew();

	}	
}