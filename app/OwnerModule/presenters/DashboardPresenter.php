<?php

namespace OwnerModule;


class DashboardPresenter extends BasePresenter {
	
	public function actionDefault() {
		$this->template->steps = \OwnerModule\RentalEditPresenter::getSteps();
	}

}
