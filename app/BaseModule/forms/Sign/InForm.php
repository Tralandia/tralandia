<?php

namespace BaseModule\Forms\Sign;

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

		$this->addPassword('password', 'o997')
			->setOption('help', '<a href="#">{_o8826}</a>')
			;

		$this->addSubmit('submit', 'o1101');

		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function setDefaultsValues()
	{

	}

	public function onSuccess(InForm $form) {
		$values = $form->getValues();
		try {
			$presenter = $this->getPresenter();
			/** @var $user \Nette\Security\User */
			$user = $presenter->getUser();
			$presenter->login($values->login, $values->password);

			if ($homepage = $user->getIdentity()->homepage){
				$presenter->redirect($homepage);
			}
			$presenter->redirect('this');
		} catch(\Nette\Security\AuthenticationException $e) {
			$form->addError('#zle prihlasovacie udaje');
		}
	}

}

interface IInFormFactory {
	/**
	 * @return InForm
	 */
	public function create();
}
