<?php

namespace OwnerModule;

class RentalPresenter extends BasePresenter {

	protected $rentalRepository;

	protected $rental;

	public function setContext(\Nette\DI\Container $dic) {
		$this->setProperty('rentalRepository');
	}

	public function actionEdit($id) {

	}

}
