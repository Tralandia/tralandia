<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 15/11/13 14:14
 */

namespace FrontModule;


use Nette;
use Nette\Utils\Json;
use Tralandia\Harvester\InvalidArgumentsException;

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
				$response = $this->harvesterRegistrator->registration($data);
				$this->payload->success = $response['success'];
				isset($response['message']) && $this->payload->message = $response['message'];
				isset($response['registered']) && $this->payload->registered = $response['registered'];
				isset($response['merged']) && $this->payload->registered = $response['merged'];
				isset($response['already_registered']) && $this->payload->alreadyRegistered = $response['already_registered'];
				isset($response['rental']) && $this->payload->rental = $response['rental']->getId();
			} catch(\Exception $e) {
				$this->payload->success = FALSE;
				$this->payload->message = $e->getMessage();
				if($e instanceof InvalidArgumentsException) {
					$this->payload->error = 'invalidData';
				} else {
					$this->payload->error = 'error';
				}
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
