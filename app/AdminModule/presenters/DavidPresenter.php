<?php

namespace AdminModule;

use Nette;

class DavidPresenter extends BasePresenter {


	public function actionList() {

		$medium = $this->context->mediumServiceFactory->create();
		$medium->setContentFromUrl('http://www.tralandia.sk/u/37/13216276260541.png');

	}


	

}
