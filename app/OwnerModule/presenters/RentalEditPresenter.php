<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/03/14 13:04
 */

namespace OwnerModule;


use Nette;

class RentalEditPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var string
	 */
	protected $step;

	public static $steps = [
		'about' => [],
		'media' => [],
		'prices' => [],
		'amenities' => [],
		'interview' => [],
	];

	protected function startup()
	{
		parent::startup();
		$stepsOrder = array_keys(self::$steps);
		$i = 0;
		foreach(self::$steps as $key => $value) {
			self::$steps[$key]['slug'] = $key;
			self::$steps[$key]['name'] = $key;
			self::$steps[$key]['nextStep'] = Nette\Utils\Arrays::get($stepsOrder, $i + 1, NULL);
			$i++;
		}
	}


	public function actionDefault($id, $step = 'about')
	{
		$this->rental = $this->findRental($this->getParameter('id'), TRUE);
		$this->checkPermission($this->rental, 'edit');

		$this->step = $step;

		$this->setView('edit');
		$this->template->rental = $this->rental;
		$this->template->editFormName = $editFormName = $step . 'Form';
		$this->template->currentStep = $this->step;
		$this->template->steps = self::$steps;

		$this[$editFormName]->onSuccess[] = function () {
			$this->redirect('this', ['step' => self::$steps[$this->step]['nextStep']]);
		};
	}


	protected function createComponentAboutForm(\OwnerModule\RentalEdit\IAboutFormFactory $factory)
	{
		$component = $factory->create($this->rental);

		return $component;
	}

	protected function createComponentMediaForm(\OwnerModule\RentalEdit\IMediaFormFactory $factory)
	{
		$component = $factory->create($this->rental);

		return $component;
	}

	protected function createComponentPricesForm(\OwnerModule\RentalEdit\IPricesFormFactory $factory)
	{
		$component = $factory->create($this->rental);

		return $component;
	}

	protected function createComponentAmenitiesForm(\OwnerModule\RentalEdit\IAmenitiesFormFactory $factory)
	{
		$component = $factory->create($this->rental);

		return $component;
	}

	protected function createComponentInterviewForm(\OwnerModule\RentalEdit\IInterviewFormFactory $factory)
	{
		$component = $factory->create($this->rental);

		return $component;
	}

}
