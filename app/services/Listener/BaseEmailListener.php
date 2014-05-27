<?php
namespace Listener;

use Entity\Language;
use Entity\Location\Location;
use Environment\IEnvironmentFactory;
use Mail\Compiler;
use Mail\ICompilerFactory;
use Nette;
use Nette\Mail\IMailer;
use Doctrine\ORM\EntityManager;

abstract class BaseEmailListener extends Nette\Object implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Nette\Mail\IMailer
	 */
	protected $mailer;

	/**
	 * @var ICompilerFactory
	 */
	protected $emailCompilerFactory;

	/**
	 * @var \Environment\IEnvironmentFactory
	 */
	protected $environmentFactory;
	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Mail\IMailer $mailer
	 * @param ICompilerFactory $emailCompilerFactory
	 * @param \Environment\IEnvironmentFactory $environmentFactory
	 */
	public function __construct(EntityManager $em, IMailer $mailer,
								ICompilerFactory $emailCompilerFactory, IEnvironmentFactory $environmentFactory)
	{
		$this->em = $em;
		$this->mailer = $mailer;
		$this->emailCompilerFactory = $emailCompilerFactory;
		$this->environmentFactory = $environmentFactory;
	}

	/**
	 * @param string $slug
	 *
	 * @return \Entity\Email\Template|NULL
	 */
	protected function getTemplate($slug)
	{
		return $this->em->getRepository(EMAIL_TEMPLATE_ENTITY)->findOneBySlug($slug);
	}


	/**
	 * @param Location $location
	 * @param Language $language
	 *
	 * @return \Mail\Compiler
	 */
	protected function createCompiler(Location $location, Language $language)
	{
		$environment = $this->environmentFactory->create($location, $language);
		$emailCompiler = $this->emailCompilerFactory->create($environment);
		return $emailCompiler;
	}


	protected function send(Compiler $emailCompiler, $email)
	{
		$message = new \Nette\Mail\Message();

		$body = $emailCompiler->compileBody();

		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body, FALSE);

		$message->addTo($email);

		$this->mailer->send($message);
	}

}
