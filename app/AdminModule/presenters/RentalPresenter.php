<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/25/13 8:29 AM
 */

namespace AdminModule;


use Nette;

class RentalPresenter extends AdminPresenter {

	/**
	 * @autowire
	 * @var \Tralandia\Rental\Discarder
	 */
	protected $discarder;

	public function actionDiscard($id)
	{
		$rental = $this->findRental($id);
		$this->checkPermission($rental, 'discard');

		$this->discarder->discard($rental);

	}

}
