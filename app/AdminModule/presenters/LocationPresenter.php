<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/25/13 8:29 AM
 */

namespace AdminModule;


use Nette;
use Nette\Application\UI\Form;

class LocationPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Tralandia\Location\Locations
	 */
	protected $locations;

	/**
	 * @autowire
	 * @var \Tralandia\Location\Countries
	 */
	protected $countries;


	protected function createComponentCreateLocalityForm()
	{
		$form = $this->simpleFormFactory->create();

		$countries = $this->countries->getForSelect();
		$form->addSelect('country', 'Country', $countries)
			->addRule($form::REQUIRED);

		$form->addText('name', 'Locality')
			->addRule($form::REQUIRED);

		$form->addSubmit('submit', 'find OR create');

		$form->onSuccess[] = $this->processCreateLocalityForm;

		return $form;
	}

	public function processCreateLocalityForm(Form $form)
	{
		$values = $form->getValues();

		$country = $this->findLocation($values->country);
		$locality = $this->locations->findOrCreateLocality($values->name, $country);

		$this->redirect('createLocalitySubmitted', ['id' => $locality->id]);
	}

	public function actionCreateLocalitySubmitted($id)
	{
		$locality = $this->findLocation($id);

		$this->template->locality = $locality;
	}


}
