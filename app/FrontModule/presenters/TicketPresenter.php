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

	public $ticketRepositoryAccessor;

	/**
	 * @var \Entity\Ticket\Ticket
	 */
	public $ticket;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->ticketRepositoryAccessor = $dic->ticketRepositoryAccessor;
	}

	public function actionShow($id) {
		$ticket = $this->ticketRepositoryAccessor->get()->find($id);

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