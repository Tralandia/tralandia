<?php
namespace Listener;

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
	 * @var \Extras\Email\Compiler
	 */
	protected $emailCompiler;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Nette\Mail\IMailer $mailer
	 * @param \Extras\Email\Compiler $emailCompiler
	 */
	public function __construct(EntityManager $em, IMailer $mailer, \Extras\Email\Compiler $emailCompiler)
	{
		$this->em = $em;
		$this->mailer = $mailer;
		$this->emailCompiler = $emailCompiler;
	}

	/**
	 * @param string $slug
	 *
	 * @return \Entity\Email\Template|NULL
	 */
	protected function getTemplate($slug)
	{
		return $this->em->getRepository('Entity\Email\Template')->findOneByOldId($slug);
	}

	/**
	 * @param int $id
	 *
	 * @return \Entity\Email\Layout|NULL
	 */
	protected function getLayout($id = 1)
	{
		return $this->em->getRepository('Entity\Email\Layout')->find($id);
	}
}