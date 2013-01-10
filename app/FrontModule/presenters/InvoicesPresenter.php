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

	public function actionUser(){
		$this->setLayout('layout');
	}	

	public function actionForms(){
		$this->setLayout('layout');
	}	

	public function actionCalendar(){
		$this->setLayout('adminMenu');
	}	

}