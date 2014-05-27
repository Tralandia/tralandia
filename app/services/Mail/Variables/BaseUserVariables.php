<?php
namespace Mail\Variables;

use Nette;
use Routers\BaseRoute;
use Security\Authenticator;

/**
 * BaseUserVariables class
 *
 * @author Dávid Ďurika
 */
abstract class BaseUserVariables extends Nette\Object {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Security\Authenticator
	 */
	private $authenticator;


	/**
	 * @param \Entity\User\User $user
	 * @param \Security\Authenticator $authenticator
	 */
	public function __construct(\Entity\User\User $user, Authenticator $authenticator) {
		$this->user = $user;
		$this->authenticator = $authenticator;
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
		return $this->user->getLogin();
	}


	/**
	 * @return NULL|string
	 */
	public function getVariablePassword()
	{
		return '****';
	}


	public function getVariableLoginLink(EnvironmentVariables $environment)
	{
		$hash = $this->authenticator->calculateAutoLoginHash($this->getUser());
		return $environment->link('//:Front:Sign:afterLogin', [BaseRoute::AUTOLOGIN => $hash]);
	}

	public function getVariableNewPasswordLink(EnvironmentVariables $environment)
	{
		$hash = $this->authenticator->calculateNewPasswordHash($this->getUser());
		return $environment->link('//:Front:Sign:newPassword', [BaseRoute::NEW_PASSWORD => $hash]);
	}

	public function getVariableUnsubscribeLink(EnvironmentVariables $environment)
	{
		return $environment->link('//:Front:Home:unsubscribeUser', ['email' => $this->getUser()->getEmail()]);
	}


}
