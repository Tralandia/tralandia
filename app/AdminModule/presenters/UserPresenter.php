<?php

namespace AdminModule;


class UserPresenter extends AdminPresenter {

	public function actionRegistration() {
		$this->template->form = $this['registrationForm'];
		$this->redirect('User:list');
	}

	protected function createComponentRegistrationForm($name) {
		return new \Tra\Forms\User\Registration($this, $name);
	}

}
