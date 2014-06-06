<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 23/05/14 14:23
 */

namespace AdminModule\EmailTester;


use BaseModule\Forms\ISimpleFormFactory;
use BaseModule\Forms\SimpleForm;
use Kdyby\Doctrine\EntityManager;
use Listener\INewsletterEmailSenderFactory;
use Nette;
use Tester\ITester;
use Tester\Options;

class NewsletterTesterForm extends \BaseModule\Components\BaseFormControl
{

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

	/**
	 * @var INewsletterEmailSenderFactory
	 */
	private $senderFactory;

	protected $templateDao;


	public function __construct(INewsletterEmailSenderFactory $senderFactory, ITester $tester, ISimpleFormFactory $formFactory, EntityManager $em)
	{

		$this->formFactory = $formFactory;
		$this->em = $em;
		$this->tester = $tester;
		$this->senderFactory = $senderFactory;
		$this->templateDao = $this->em->getRepository(EMAIL_TEMPLATE_ENTITY);
	}


	protected function createComponentForm()
	{
		$form = $this->formFactory->create();

		$sendTo = $this->tester instanceof Options ? $this->tester->getEmail() : NULL;
		$form->addText('sendTo', 'Send To: ')
				->setRequired(TRUE)
				->setDefaultValue($sendTo);

		$templates = $this->templateDao->findBy(['isNewsletter' => TRUE]);
		$templatesSelect = [];
		foreach($templates as $template) {
			$templatesSelect[$template->id] = $template->name;
		}
		$form->addSelect('template', 'Email Template: ', $templatesSelect)
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

		$emailArguments = [];
		foreach($this->parametersEntity as $parameter => $entityName) {
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

		$template = $this->templateDao->find($values['template']);
		$sender = $this->senderFactory->create($template);
		call_user_func_array([$sender, 'onSuccess'], $emailArguments);

		$this->onFormSuccess($form);
	}
}
