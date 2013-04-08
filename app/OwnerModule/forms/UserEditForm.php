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
		$this->addPassword('password', 'o997');

		$this->addPassword('confirmPassword', '#confirm_password')
			->addConditionOn($this["password"], self::FILLED)
				->addRule(self::EQUAL, "Hesla se musí shodovat !", $this["password"]);

		$this->addCheckbox('newsletter', '#Newsletter');

		$this->addSubmit('submit', '#save');

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
		if($values->password) {
			$user->setPassword($values->password);
		}
		$user->setNewsletter($values->newsletter);

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