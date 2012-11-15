<?php

namespace FrontModule;

class RentalPresenter extends BasePresenter {

	protected $rental;


	public function actionDetail($id) {

		if (!$id) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}
		$this->rental = $this->rentalRepositoryAccessor->get()->find($id);

		$this->template->rental = $this->rental;
	}

	public function actionList() {

		

	}

	//
	// COMPONENTS
	// 


}
