<?php
namespace Mail\Variables;

use Nette;

/**
 * OwnerVariables class
 *
 * @author Dávid Ďurika
 */
class TranslatorVariables extends BaseUserVariables {

	public function getVariablePassword() {
		$this->getUser()->getPassword();
	}

}
