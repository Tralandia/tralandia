<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 15/11/13 14:14
 */

namespace FrontModule;


use Nette;

class HarvesterPresenter extends BasePresenter
{

	public function actionRegister($token, $data)
	{
		if($this->isTokenValid($token, $data)) {
			$this->payload->success = TRUE;
			$this->sendPayload();
		} else {
			$this->terminate();
		}
	}


	protected function isTokenValid($token, $data)
	{
		return true;
	}

}
