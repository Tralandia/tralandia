<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 23/05/14 14:23
 */

namespace AdminModule\EmailTester;


use BaseModule\Forms\ISimpleFormFactory;
use BaseModule\Forms\SimpleForm;
use Doctrine\ORM\Query\ResultSetMapping;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tester\ITester;
use Tester\Options;

class EmailTesterForm extends \BaseModule\Components\BaseFormControl
{

	protected $emails = [
		['name' => 'Rental Sites 1', 'listener' => '\Listener\PsOfferEmailListener', 'method' => 'onSuccess', 'parameters' => ['rental']]
	];

	protected $parametersEntity = [
		'rental' => RENTAL_ENTITY,
	];

	/**
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	private $formFactory;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	/**
	 * @var \Tester\ITester
	 */
	private $tester;

	public $onFormSuccess = [];


	public function __construct(ITester $tester, ISimpleFormFactory $formFactory, EntityManager $em)
	{

		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->tester = $tester;
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		$sendTo = $this->tester instanceof Options ? $this->tester->getEmail() : NULL;
		$form->addText('sendTo', 'Send To: ')
				->setRequired(TRUE)
				->setDefaultValue($sendTo);

		$emails = [];
		foreach($this->emails as $key => $email) {
			$emails[$key] = $email['name'] . ' (' . implode(', ', $email['parameters']).')';
		}
		$form->addSelect('email', 'Email Template: ', $emails)
				->setPrompt('-- email --')
				->setRequired(TRUE);

		$form->addText('rental', 'Rental ID: ')
			->addCondition($form::FILLED)
			->addRule($form::INTEGER);

		$form->addSubmit('submit', 'Send');

		$form->onSuccess[] = $this->processForm;

		return $form;
	}

	public function processForm(SimpleForm $form)
	{
		$values = $form->getValues(TRUE);

		$emailSettings = $this->emails[$values['email']];

		$emailArguments = [];
		foreach($emailSettings['parameters'] as $parameter) {
			$entityName = $this->parametersEntity[$parameter];
			$repository = $this->em->getRepository($entityName);
			if($values[$parameter]) {
				$emailArguments[] = $repository->find($values[$parameter]);
			} else {
				$query = 'select e.id from ' . $parameter . ' e order by rand() limit 1';
				$statement = $this->em->getConnection()->executeQuery($query);
				$result = $statement->fetch();
				$entity = $repository->find($result['id']);
				$emailArguments[] = $entity;
			}
		}

		$context = $this->getPresenter()->getContext();
		$context->removeService('tester');
		$context->addService('tester', new \Tester\Options($values['sendTo'], $this->getPresenter()->getSession()));

		$listener = $this->getPresenter()->getContext()->getByType($emailSettings['listener']);
		call_user_func_array([$listener, $emailSettings['method']], $emailArguments);

		$this->onFormSuccess($form);
	}
}
