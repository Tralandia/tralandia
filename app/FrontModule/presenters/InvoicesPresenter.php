<?php 
namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;

class InvoicesPresenter extends BasePresenter {

	public function renderDefault() {

	}

	public function actionList(){
		$this->setLayout('adminMenu');
	}

}