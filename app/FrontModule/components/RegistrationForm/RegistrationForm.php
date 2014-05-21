<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 11/04/14 08:13
 */

namespace FrontModule\RegistrationForm;


use BaseModule\Components\BaseFormControl;
use BaseModule\Forms\BaseForm;
use BaseModule\Forms\ISimpleFormFactory;
use Entity\User\Role;
use Environment\Environment;
use Kdyby\Doctrine\EntityManager;
use Nette;

class RegistrationForm extends BaseFormControl
{
	public $onFormSuccess = [];

	protected $user;

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;


	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var \Kdyby\Doctrine\EntityDao
	 */
	protected $userRepository;

	/**
	 * @var \User\UserCreator
	 */
	private $userCreator;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	public function __construct(\User\UserCreator $userCreator, Environment $environment, ISimpleFormFactory $formFactory, EntityManager $em)
	{
		parent::__construct();
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->userRepository = $this->em->getRepository(USER_ENTITY);
		$this->userCreator = $userCreator;
		$this->environment = $environment;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create();


		$form->addText('email', 'o1096')
			->setOption('help', $this->translate('o3095'))
			->setOption('prepend', '<i class="icon-envelope"></i>')
			->setAttribute('placeholder', 'email@email.com')
			->setRequired($this->translate('o3095'))
			->addRule($form::EMAIL, $this->translate('o100144'));

		$form->addPassword('password', 'o997')
			->setOption('help', $this->translate('o100145'))
			->setOption('prepend', '<i class="icon-lock"></i>')
			->setRequired($this->translate('o100145'))
			->addRule($form::MIN_LENGTH, $this->translate('o100145'), 5);

		$form->addPassword('passwordAgain', '721627')
			->setOption('prepend', '<i class="icon-lock"></i>')
			->setRequired($this->translate('o100145'))
			->addRule($form::EQUAL, '', $form['password']);

		$form->addSubmit('submit', 'o1099');

		$form->onValidate[] = $this->validateForm;
		$form->onSuccess[] = $this->processForm;

		return $form;
	}


	public function validateForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

		$user = $this->userRepository->findOneByLogin($values->email);
		if ($user) {
			$form->addError('o2610', 'email');
		}


	}


	public function processForm(BaseForm $form)
	{
		$values = $form->getFormattedValues();

		$user = $this->userCreator->create($values->email, $this->environment, Role::OWNER);
		$user->setPassword($values->password);

		$this->em->persist($user);
		$this->em->flush();

		$this->onFormSuccess($form, $user);
	}

}


interface IRegistrationFormFactory
{
	public function create();
}
