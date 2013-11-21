<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 15/11/13 14:14
 */

namespace FrontModule;


use Nette;
use Nette\Utils\Json;

class HarvesterPresenter extends BasePresenter
{

	const TOKEN_SALT = '4d\e24hRwj';


	public function actionRegister($token)
	{
		$post = $this->getRequest()->getPost();
		$data = Nette\Utils\Arrays::get($post, 'data', '');
		$data = urldecode($data);
		if($this->isTokenValid($token, $data)) {
			$data = Json::decode($data, Json::FORCE_ARRAY);
			$this->payload->success = TRUE;
			$this->sendPayload();
		} else {
			$this->terminate();
		}
	}


	/**
	 * @param $token
	 * @param $data
	 *
	 * @return bool
	 */
	protected function isTokenValid($token, $data)
	{
		$myToken = sha1($data . self::TOKEN_SALT);
		return $myToken == $token;
	}

}
