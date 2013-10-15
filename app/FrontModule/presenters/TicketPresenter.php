<?php
namespace FrontModule;

use Nette;
use Extras;

class TicketPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \FrontModule\Forms\ITicketFormFactory
	 */
	protected $ticketFormFactory;


	/**
	 * @var \Entity\Ticket\Ticket
	 */
	public $ticket;


	public function actionShow($id) {
		$ticket = $this->getContext()->getService('doctrine.default.entityManager')->getDao(TICKET_ENTITY)->find($id);

		if(!$ticket) {
			$this->redirect('Home:');
		}
		$this->ticket = $ticket;

		$this->template->ticket = $ticket;
	}

	protected function createComponentTicketForm()
	{
		$form = $this->ticketFormFactory->create($this->loggedUser, $this->ticket);

		$form->onSuccess[] = function ($form) {
			if ($form->valid) $form->presenter->redirect('this');
		};

		return $form;
	}


}
