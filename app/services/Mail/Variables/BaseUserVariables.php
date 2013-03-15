<?php
namespace Mail\Variables;

use Nette;

/**
 * BaseUserVariables class
 *
 * @author Dávid Ďurika
 */
abstract class BaseUserVariables extends Nette\Object {

	/**
	 * @var \Entity\User\User
	 */
	private $user;

	/**
	 * @param \Entity\User\User $user
	 */
	public function __construct(\Entity\User\User $user) {
		$this->user = $user;
	}

	/**
	 * @return \Entity\User\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return string
	 */
	public function getVariableEmail() {
		return 'janko@hrasko.com';
	}

}