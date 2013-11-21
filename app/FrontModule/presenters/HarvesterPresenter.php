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

	/**
	 * @autowire
	 * @var \Tralandia\Harvester\ProcessingData
	 */
	protected $harvesterDataValidator;

	/**
	 * @autowire
	 * @var \Tralandia\Harvester\RegistrationData
	 */
	protected $harvesterRegistrator;

	const TOKEN_SALT = '4d\e24hRwj';


	public function actionRegister($token)
	{
		$post = $this->getRequest()->getPost();
		$data = Nette\Utils\Arrays::get($post, 'data', '');
		$data = urldecode($data);

		if($this->isTokenValid($token, $data)) {
			try {
				$data = Json::decode($data, Json::FORCE_ARRAY);
				$data = $this->harvesterDataValidator->process($data);
				$rental = $this->harvesterRegistrator->registration($data);
				$this->payload->success = TRUE;
			} catch(\Exception $e) {
				$this->payload->success = FALSE;
				//throw $e;
			}

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
