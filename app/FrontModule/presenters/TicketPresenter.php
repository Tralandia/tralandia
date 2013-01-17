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

	}	
}