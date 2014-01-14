<?php
namespace Listener;

use Nette;
use \Entity\Contact\PotentialMember;

class PotentialMemberEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
//		return ['FormHandler\RegistrationHandler::onSuccess'];
		return [];
	}


	public function onSuccess(PotentialMember $potentialEntity)
	{
		$emailCompiler = $this->prepareCompiler($potentialEntity);

		$email = $potentialEntity->getEmail();

		$this->send($emailCompiler, $email);
	}


	/**
	 * @param \Entity\Contact\PotentialMember $potentialEntity
	 *
	 * @return \Mail\Compiler
	 */
	private function prepareCompiler(PotentialMember $potentialEntity)
	{
		$emailCompiler = $this->createCompiler($potentialEntity->getPrimaryLocation(), $potentialEntity->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('potental-member'));

		$emailCompiler->addPotentialMember('pm', $potentialEntity);

		return $emailCompiler;
	}

}
