<?php
namespace Listener;

use Nette;

class ForgotPasswordEmailListener extends Nette\Object implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Extras\Email\Compiler
	 */
	protected $emailCompiler;

	public function __construct(\Extras\Email\Compiler $emailCompiler)
	{
		$this->emailCompiler = $emailCompiler;
	}

	public function getSubscribedEvents()
	{
		return ['\BaseModule\Forms\ForgotPasswordForm::onAfterProcess'];
	}

	public function onAfterProcess(\Entity\User\User $user)
	{
		d('Akoze som poslal email o zabudnutom hesle');
	}
}