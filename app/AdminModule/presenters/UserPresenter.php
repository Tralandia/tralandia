<?php

namespace AdminModule;


class UserPresenter extends AdminPresenter {

	public function actionRegistration() {
		$this->template->form = $this['registrationForm'];
	}

	protected function createComponentRegistrationForm($name) {
		return new \Tra\Forms\User\Registration($this, $name);
	}

}
