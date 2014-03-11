<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;
use Tralandia\Location\Countries;

class OrderManagerPresenter extends BasePresenter
{
	/**
	 * @autowire
	 * @var \OwnerModule\Forms\IOrderManagerEditFormFactory
	 */
	protected $orderManagerEditFormFactory;

	/**
	 * @autowire
	 * @var \Tralandia\Location\Countries
	 */
	protected $countries;

	public function createComponentOrderManagerEditForm()
	{
		$form = $this->orderManagerEditFormFactory->create($this->loggedUser, $this->countries);

		return $form;
	}

}
