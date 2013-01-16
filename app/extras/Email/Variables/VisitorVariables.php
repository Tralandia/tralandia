<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * VisitorVariables class
 *
 * @author Dávid Ďurika
 */
class VisitorVariables extends Nette\Object {

	/**
	 * @var \Entity\User\User
	 */
	private $visitor;

	/**
	 * @param \Entity\User\User $visitor
	 */
	public function __construct(\Entity\User\User $visitor) {
		$this->visitor = $visitor;
	}

	/**
	 * @return string
	 */
	public function getVariableEmail() {
		return 'janko@hrasko.com';
	}
}

interface IVisitorVariablesFactory {
	/**
	 * @param \Entity\User\User $visitor
	 *
	 * @return VisitorVariables
	 */
	function create(\Entity\User\User $visitor);
}