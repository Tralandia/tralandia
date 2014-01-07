<?php
namespace Listener;

use Entity\Contact\PotentialMemberEntity;
use Nette;

class PotentialMemberEmailListener extends BaseEmailListener implements \Kdyby\Events\Subscriber
{

	public function getSubscribedEvents()
	{
//		return ['FormHandler\RegistrationHandler::onSuccess'];
		return [];
	}


	public function onSuccess(PotentialMemberEntity $potentialEntity)
	{
		$emailCompiler = $this->prepareCompiler($potentialEntity);

		$email = $potentialEntity->getEmail();

		$this->send($emailCompiler, $email);
	}


	/**
	 * @param \Entity\Contact\PotentialMemberEntity $potentialEntity
	 *
	 * @return \Mail\Compiler
	 */
	private function prepareCompiler(PotentialMemberEntity $potentialEntity)
	{
		$emailCompiler = $this->createCompiler($potentialEntity->getPrimaryLocation(), $potentialEntity->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('potental-member'));

		$emailCompiler->addPotentialMember('pm', $potentialEntity);

		return $emailCompiler;
	}

}
