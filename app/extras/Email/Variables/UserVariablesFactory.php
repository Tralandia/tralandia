<?php

namespace Extras\Email\Variables;

use Extras\Models\Service\ServiceFactory;
/**
 * UserVariablesFactory class
 *
 * @author Dávid Ďurika
 */
class UserVariablesFactory {

	protected $userServiceFactory;

	protected $variablesClass;

	public function __construct($variablesClass, ServiceFactory $userServiceFactory) {
		$this->variablesClass = $variablesClass;
		$this->userServiceFactory = $userServiceFactory;
	}

	public function create(\Entity\User\User $user) {
		$variablesClass = 'Extras\Email\Variables\\'.$this->variablesClass;
		return new $variablesClass($this->userServiceFactory->create($user));
	}
}