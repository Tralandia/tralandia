<?php
namespace Mail\Variables;

use Entity\Contact\PotentialMember;
use Entity\Contact\PotentialMemberEntity;
use Nette;

/**
 * PotentialMemberVariables class
 *
 * @author Dávid Ďurika
 */
class PotentialMemberVariables extends Nette\Object
{


	/**
	 * @var \Entity\Contact\PotentialMember
	 */
	private $potentialMember;


	/**
	 * @param \Entity\Contact\PotentialMember $potentialMember
	 */
	public function __construct(PotentialMember $potentialMember)
	{
		$this->potentialMember = $potentialMember;
	}


	/**
	 * @return \Entity\Language
	 */
	public function getEntity()
	{
		return $this->potentialMember;
	}


	public function getVariableUnsubscribeLink(EnvironmentVariables $environment)
	{
		return $environment->link('//:Front:Home:unsubscribe', ['email' => $this->potentialMember->getEmail()]);

	}

}
