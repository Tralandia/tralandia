<?php

namespace OwnerModule;


class CalendarWidgetPresenter extends BasePresenter {

	public function actionDefault()
	{

		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->linkTemplate = $this->link(
			'//:Front:CalendarIframe:default',
			['rentalId' => '__rentalId__', 'monthsCount' => '__monthsCount__']
		);
	}

}
