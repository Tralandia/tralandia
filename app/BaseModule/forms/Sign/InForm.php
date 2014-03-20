<?php

namespace BaseModule\Forms\Sign;

use AdminModule\BasePresenter;
use Nette;

class InForm extends \BaseModule\Forms\BaseForm {

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(Nette\Localization\ITranslator $translator)
	{
		parent::__construct($translator);
	}

	protected function buildForm() {
		$this->addText('login', 'o1096');

		$helpText = $this->translate('o8826');
		$help = Nette\Utils\Html::el('a')
				->href('#')->setText($helpText)
				->class('toggle')
				->for('forgotPasswordForm');

		$this->addPassword('password', 'o997')
			->setOption('help', $help)
			;

		$this->addSubmit('submit', 'o1101');

		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function setDefaultsValues()
	{

	}

	public function onSuccess(InForm $form) {
		$values = $form->getValues();

		/** @var $presenter \BasePresenter */
		$presenter = $this->getPresenter();

		try {
			$identity = $presenter->redirectToCorrectDomain($values->login, $values->password);
			$presenter->login($identity);
			$presenter->actionAfterLogin();

		} catch(\Nette\Security\AuthenticationException $e) {
			$form->presenter->flashMessage($this->translate('o1119'), BasePresenter::FLASH_ERROR);
		}

	}

}

interface IInFormFactory {
	/**
	 * @return InForm
	 */
	public function create();
}
