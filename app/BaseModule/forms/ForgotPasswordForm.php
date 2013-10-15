<?php

namespace BaseModule\Forms;

use Nette;
use Tralandia\BaseDao;

class ForgotPasswordForm extends \BaseModule\Forms\BaseForm {

	/**
	 * @var array
	 */
	public $onAfterProcess = [];

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $userDao;


	/**
	 * @param \Tralandia\BaseDao $userDao
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(BaseDao $userDao, Nette\Localization\ITranslator $translator)
	{
		$this->userDao = $userDao;
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
		if(!$this['email']->hasErrors() && !$user = $this->userDao->findOneByLogin($values->email)) {
			$this['email']->addError($this->translate('152282'));
		}
	}


	/**
	 * @param ForgotPasswordForm $form
	 */
	public function process(ForgotPasswordForm $form)
	{
		$values = $form->getValues();
		$user = $this->userDao->findOneByLogin($values->email);
		$this->onAfterProcess($user);
	}


}

interface IForgotPasswordFormFactory {
	/**
	 * @return ForgotPasswordForm
	 */
	public function create();
}
