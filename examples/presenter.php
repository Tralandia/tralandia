<?php

class RentalPresenter extends BasePresenter {
	
	public function renderList() {
		//$user = $this->em->find('User', $this->user->id);
		//$this->template->clients = $user->clients;
	}

	public function renderAdd() {
		$this->template->form = $this->getComponent('form');
	}	
	
	public function renderEdit($id = 0) {
		$form = $this->getComponent('form');
		$row = $this->em->find('Client', $id);
		
		if (!$row) {
			throw new NA\BadRequestException('Record not found');
		}
		
		if (!$form->isSubmitted()) {
			$form->setDefaults($row);
		}
		
		$this->template->client = $row;
		$this->template->form = $form;
	}
	
	protected function createComponentForm($name) {
		return new Tra\Forms\Rental($this, $name);
	}
}