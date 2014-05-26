<?php

namespace OwnerModule\Forms;

use Entity\Rental\Rental;
use Entity\User\User;
use Nette\Forms\Form;
use Nette\Localization\ITranslator;
use Tralandia\BaseDao;

class UserEditForm extends BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $userDao;


	/**
	 * @param \Entity\User\User $user
	 * @param \Tralandia\BaseDao $userDao
	 * @param \Nette\Localization\ITranslator $translator
	 */
	public function __construct(User $user, BaseDao $userDao, ITranslator $translator){
		$this->user = $user;
		$this->userDao = $userDao;
		parent::__construct($translator);
	}


	public function buildForm() {
		$this->addText('login', 'o1096');
		$this->addPassword('passwordOld', 'o100157');
		$this->addPassword('password', 'o997');

		$this->addPassword('confirmPassword', 'o100148')
			->addConditionOn($this["password"], self::FILLED)
				->addRule(self::EQUAL, 'o100149', $this["password"]);

		$this->addCheckbox('newsletter', '792460');

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
		$user->setNewsletter($values->newsletter);

		$this->userDao->save($user);
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
