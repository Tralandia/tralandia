<?php

namespace OwnerModule;


class CalendarPresenter extends BasePresenter {

	public function actionDefault($id)
	{

	}


	public function createComponentCalendarForm()
	{
		$form = $this->simpleFormFactory->create();
		$form->addCalendarContainer('calendar', 'Calendar');

		$form->addSubmit('submit', 'save');

		return $form;
	}

}
