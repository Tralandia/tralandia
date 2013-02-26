<?php

namespace OwnerModule;


class CalendarWidgetPresenter extends BasePresenter {

	public function actionDefault()
	{

		$this->template->rentals = $this->loggedUser->getRentals();
	}

}
