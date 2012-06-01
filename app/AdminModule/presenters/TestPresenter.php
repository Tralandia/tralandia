<?php

namespace AdminModule;


class TestPresenter extends BasePresenter {

	public function actionFileUpload() {

	}

	public function actionPhraseControl() {
		
	}

	public function actionContactsControl() {
		
	}

	/**
	 * @return \AdminModule\Components\GaleryManager
	 */
	protected function createComponentGaleryManager()
	{
		$comp = new \AdminModule\Components\GaleryManager;
	
		return $comp;
	}


}
