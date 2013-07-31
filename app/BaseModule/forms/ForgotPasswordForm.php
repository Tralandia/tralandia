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

		$this->onSuccess[] = callback($this, 'process');
		$this->onValidate[] = callback($this, 'validation');
	}

	public function setDefaultsValues()
	{

	}

	public function validation(ForgotPasswordForm $form)
	{
		$values = $form->getValues();
		if(!$this['email']->hasErrors() && !$user = $this->userRepositoryAccessor->get()->findOneByLogin($values->email)) {
			$this['email']->addError($this->translate('152282'));
		}
	}


	/**
	 * @param ForgotPasswordForm $form
	 */
	public function process(ForgotPasswordForm $form)
	{
		$values = $form->getValues();
		$user = $this->userRepositoryAccessor->get()->findOneByLogin($values->email);
		$this->onAfterProcess($user);
	}


}

interface IForgotPasswordFormFactory {
	/**
	 * @return ForgotPasswordForm
	 */
	public function create();
}
