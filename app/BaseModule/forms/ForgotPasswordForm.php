<?php

namespace BaseModule\Forms;

use Nette;

class ForgotPasswordForm extends \BaseModule\Forms\BaseForm {

	public $onAfterProcess = array();

	/**
	 * @var \Extras\Models\Repository\RepositoryAccessor
	 */
	protected $userRepositoryAccessor;

	/**
	 * @param \Extras\Models\Repository\RepositoryAccessor $userRepositoryAccessor
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(\Extras\Models\Repository\RepositoryAccessor $userRepositoryAccessor, Nette\Localization\ITranslator $translator)
	{
		$this->userRepositoryAccessor = $userRepositoryAccessor;
		parent::__construct($translator);
	}

	protected function buildForm()
	{
		$this->addText('email', 'o1096')
			->addRule(self::EMAIL);

		$this->addSubmit('submit', 'o100141');
		$this->addButton('cancel', '#Cancel');

		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function setDefaultsValues()
	{

	}

	/**
	 * @param ForgotPasswordForm $form
	 */
	public function onSuccess(ForgotPasswordForm $form)
	{
		$values = $form->getValues();
		if($user = $this->userRepositoryAccessor->get()->findOneByLogin($values->email)) {
			$this->onAfterProcess($user);
		} else {
			$this['email']->addError('#zly email');
		}
	}

}

interface IForgotPasswordFormFactory {
	/**
	 * @return ForgotPasswordForm
	 */
	public function create();
}
