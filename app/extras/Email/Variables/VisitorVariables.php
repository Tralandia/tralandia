<?php
namespace Extras\Email\Variables;

use Nette;

/**
 * VisitorVariables class
 *
 * @author Dávid Ďurika
 */
class VisitorVariables extends Nette\Object {

	private $visitor;

	public function __construct(\Service\User\UserService $visitor) {
		$this->visitor = $visitor;
	}

	public function getVariableEmail() {
		return 'janko@hrasko.com';
	}
}