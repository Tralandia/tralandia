<?php

namespace OwnerModule;


use BaseModule\Forms\SimpleForm;

class CalendarPresenter extends BasePresenter
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $currentRental;


	/**
	 * @autowire
	 * @var \Extras\Translator
	 */
	protected $translator;

	/**
	 * @autowire
	 * @var \Environment\Collator
	 */
	protected $collator;

	public function createComponentCalendarForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addCalendarContainer('calendar', 'Calendar', $this->currentRental->getCalendar());

		// $form->addSubmit('submit', 'o100083');

		$form->onSuccess[] = $this->processCalendarForm;

		return $form;
	}

	public function processCalendarForm(SimpleForm $form)
	{
		$values = $form->getFormattedValues(TRUE);

		$rental = $this->currentRental;

		$rental->updateCalendar($values['calendar']['data']);

		$this->em->flush($rental);

		$this->payload->success = TRUE;
		$this->sendPayload();
	}

	public function actionDefault($id)
	{
		$this->currentRental = $this->findRental($id);
		$this->template->environment = $this->environment;
		$this->template->thisRental = $this->currentRental;
		$this->template->languages = $this->languageRepositoryAccessor->get()->getSupportedForSelect(
			$this->translator,
			$this->collator
		);

		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->linkTemplate = $this->link(
			':Owner:CalendarWidget:generateCode',
			['id' => '__rental__', 'wLanguage' => '__language__', 'columns' => '__columns__', 'rows' => '__rows__']
		);
	}

	public function actionGenerateCode($id, $wLanguage, $columns, $rows)
	{
		$language = $this->languageRepositoryAccessor->get()->find($wLanguage);
		$rental = $this->findRental($id);
		if(!$language) {
			throw new BadRequestException;
		}

		$months = $columns * $rows;

		$monthWidth = 136;
		$monthHeight = 150;

		$iFrameWidth = ($monthWidth * $columns)+ 10;
		$iFrameHeight = ($monthHeight * $rows)+ 10;

		$link = $this->link(
			'//:Front:CalendarIframe:default',
			['language' => $language, 'rental' => $rental, 'months' => $months]
		);

		$code = Html::el('iframe')
			->src($link)
			->scrolling('no')
			->style("width: {$iFrameWidth}px ;height: {$iFrameHeight}px; border:none; margin:0 auto;");

		$json = ['code' => htmlspecialchars_decode("$code")];
		$this->sendJson($json);
	}

}
