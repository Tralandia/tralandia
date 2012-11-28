<?php 

namespace OwnerModule\Forms;

use Entity\Rental\Rental;

class RentalEditForm extends BaseForm {

	protected $rental;

	public function __construct(Rental $rental){
		$this->rental = $rental;
		parent::__construct();
	}

	protected function buildForm() {

	}	

}