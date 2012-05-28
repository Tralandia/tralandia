<?php

namespace AdminModule;


class TestPresenter extends BasePresenter {

	public function actionFileUpload() {

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
