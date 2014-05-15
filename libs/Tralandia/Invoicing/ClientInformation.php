<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 14/05/14 15:15
 */

namespace Tralandia\Invoicing;


use Nette;
use Nette\Utils\Json;
use Tralandia\User\User;

class ClientInformation
{

	protected $info = [];

	/**
	 * @var \Tralandia\User\User
	 */
	private $user;


	/**
	 * @param array $info
	 * @param User $user
	 */
	public function __construct(array $info, User $user = null)
	{
		foreach($info as $key => $value) {
			$this->setInfo($value, $key);
		}

		$this->user = $user;
	}


	/**
	 * @param $info
	 * @param string $type
	 */
	public function setInfo($info, $type = 'default')
	{
		$this->info[$type] = $info;
	}


	/**
	 * @param string $type
	 *
	 * @return mixed
	 */
	public function getInfo($type = 'default')
	{
		$info = Nette\Utils\Arrays::get($this->info, $type, []);
		if(!isset($info['email']) && $this->user) $info['email'] = $this->user->login;

		return $info;
	}

	public function fillValue($array, $name, $onUser)
	{
		if(!isset($info['email']) && $this->user) $info['email'] = $this->user->login;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return Json::encode($this->info);
	}

}
