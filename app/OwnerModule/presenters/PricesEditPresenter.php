<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/03/14 13:04
 */

namespace OwnerModule;


use Nette;

class PricesEditPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	public function actionDefault($id)
	{
		$this->rental = $this->findRental($id, TRUE);
		$this->checkPermission($this->rental, 'edit');

	}

	protected function createComponentPricesEditForm(\OwnerModule\Forms\IPricesEditFormFactory $factory)
	{
		return $factory->create($this->rental, $this->environment);
	}

}
