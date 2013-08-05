<?php

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Entity\User\User;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;

class UserEditForm extends BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	protected $userRepositoryAccessor;


	/**
	 * @param \Entity\User\User $user
	 * @param $userRepositoryAccessor
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(User $user, $userRepositoryAccessor, ITranslator $translator){
		$this->user = $user;
		$this->userRepositoryAccessor = $userRepositoryAccessor;
		parent::__construct($translator);
	}


	public function buildForm() {
		$this->addText('login', 'o1096');
		$this->addPassword('passwordOld', 'o100157');
		$this->addPassword('password', 'o997');

		$this->addPassword('confirmPassword', 'o100148')
			->addConditionOn($this["password"], self::FILLED)
				->addRule(self::EQUAL, 'o100149', $this["password"]);

//		$this->addCheckbox('newsletter', 'o100150');

		$this->addSubmit('submit', 'o100151');

		$this->onSuccess[] = [$this, 'process'];
	}

	public function setDefaultsValues()
	{
		$user = $this->user;
		$this->setDefaults([
			'login' => $user->getLogin(),
			'newsletter' => $user->getNewsletter(),
		]);
	}

	public function process(UserEditForm $form)
	{
		$values = $form->getValues();

		$user = $this->user;
		$user->setLogin($values->login);
		if($values->passwordOld == $user->getPassword() && $values->password == $values->confirmPassword) {
			$user->setPassword($values->password);
		}
//		$user->setNewsletter($values->newsletter);

		$this->userRepositoryAccessor->get()->update($user);
	}

}

interface IUserEditFormFactory {
	/**
	 * @param \Entity\User\User $user
	 *
	 * @return UserEditForm
	 */
	public function create(User $user);
}
