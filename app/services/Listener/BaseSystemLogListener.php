<?php
namespace Listener;

use Entity\Language;
use Entity\Location\Location;
use Environment\IEnvironmentFactory;
use Mail\ICompilerFactory;
use Nette;
use Nette\Mail\IMailer;
use Doctrine\ORM\EntityManager;

abstract class BaseSystemLogListener extends Nette\Object implements \Kdyby\Events\Subscriber
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;


	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	/**
	 * @param $slug
	 *
	 * @return \Entity\Log\System
	 */
	protected function createLog($slug)
	{
		/** @var $log \Entity\Log\System */
		$log = $this->em->getRepository(SYSTEM_LOG_ENTITY)->createNew();
		$log->setSlug($slug);
		return $log;
	}

}
