<?php
namespace Listener;

use Entity\Email\Template;
use Environment\IEnvironmentFactory;
use Doctrine\ORM\EntityManager;
use Mail\ICompilerFactory;
use Nette;
use Nette\Mail\IMailer;

class NewsletterEmailSender extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Entity\Email\Template
	 */
	protected $template;

	public function __construct(Template $template, EntityManager $em, IMailer $mailer,
								ICompilerFactory $emailCompilerFactory, IEnvironmentFactory $environmentFactory)
	{
		parent::__construct($em, $mailer, $emailCompilerFactory, $environmentFactory);
		$this->template = $template;
	}

	public function getSubscribedEvents()
	{
		return [];
	}


	public function onSuccess(\Entity\Rental\Rental $rental)
	{
		$emailCompiler = $this->prepareCompiler($rental);

		$email = $rental->getContactEmail();

		$this->send($emailCompiler, $email);
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return \Mail\Compiler
	 */
	private function prepareCompiler(\Entity\Rental\Rental $rental)
	{
		$receiver = $rental->getOwner();

		$emailCompiler = $this->createCompiler($receiver->getPrimaryLocation(), $receiver->getLanguage());
		$emailCompiler->setTemplate($this->template);
		$emailCompiler->addRental('rental', $rental);
		$emailCompiler->addOwner('owner', $receiver);

		return $emailCompiler;
	}

}


interface INewsletterEmailSenderFactory
{
	public function create(Template $template);
}
