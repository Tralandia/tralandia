<?php

namespace AdminModule\Forms\Acl;

use Nette\Utils\Neon;

class PresenterEdit extends \AdminModule\Forms\Form {

	public $destinationDir;
	public $presenterName;

	public function __construct($parent, $name, $presenterActions, $roles) {
		parent::__construct($parent, $name);

		$assertions = array(
			'deny' => 'No way!',
			'allow' => 'Yes',
			'assert1' => 'Test',
		);

		foreach ($presenterActions as $key => $value) {
			$cont = $this->addContainer($key);
			foreach ($roles as $roleId => $role) {
				$cont->addSelect($role, $role, $assertions)
					->getControlPrototype()->addClass('input-small');
			}
		}

		$this->addSubmit('submit', 'OK maan');
		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function onSuccess(PresenterEdit $form) {
		$values = $this->getValues(TRUE);

		$acl = Neon::encode($values, Neon::BLOCK);

		$resource = $this->presenterName;
		@mkdir($this->destinationDir, 0777);
		file_put_contents($this->destinationDir . '/' .str_replace(':', '-', $resource) . '.neon', trim($acl));
	}

}
