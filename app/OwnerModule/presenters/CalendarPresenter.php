<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;
use Nette\Application\BadRequestException;
use Nette\Utils\Html;

class CalendarPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $currentRental;

	/**
	 * @autowire
	 * @var \Tralandia\Language\Languages
	 */
	protected $languages;

	/**
	 * @autowire
	 * @var \Tralandia\RentalSearch\InvalidateRentalListener
	 */
	protected $invalidateRentalListener;

	public function createComponentCalendarForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addCalendarContainer('calendar', 'Calendar', $this->currentRental);

		// $form->addSubmit('submit', 'o100083');

		$form->onSuccess[] = $this->processCalendarForm;

		return $form;
	}

	public function processCalendarForm(SimpleForm $form)
	{
		$values = $form->getFormattedValues(TRUE);

		$rental = $this->currentRental;

		$rental->updateOldCalendar($values['calendar']['data']);

		$this->em->flush($rental);

		$this->invalidateRentalListener->onSuccess($rental);

		$this->payload->success = TRUE;
		$this->sendPayload();
	}

	public function actionDefault($id)
	{
		$this->currentRental = $this->findRental($id);
		$this->template->rental = $this->currentRental;
		$this->template->environment = $this->environment;
		$this->template->languages = $this->languages->getSupportedForSelect();

		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->linkTemplate = $this->link(
			'generateCode',
			['id' => '__rental__', 'wLanguage' => '__language__', 'columns' => '__columns__', 'rows' => '__rows__']
		);
	}

	public function actionGenerateCode($id, $wLanguage, $columns, $rows)
	{
		$language = $this->findLanguage($wLanguage);
		$rental = $this->findRental($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$months = $columns * $rows;

		$monthWidth = 136;
		$monthHeight = 150;

		$iFrameWidth = ($monthWidth * $columns)+ 10;
		$iFrameHeight = ($monthHeight * $rows)+ 10;

		$version = $rental->calendarVersion();
		$link = $this->link(
			'//:Front:CalendarIframe:default',
			['language' => $language, 'rental' => $rental, 'months' => $months, 'version' => $version]
		);

		$code = Html::el('iframe')
			->src($link)
			->scrolling('no')
			->style("width: {$iFrameWidth}px ;height: {$iFrameHeight}px; border:none; margin:0 auto;");

		$json = ['code' => htmlspecialchars_decode("$code")];
		$this->sendJson($json);
	}

}
