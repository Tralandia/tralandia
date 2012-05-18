<?php

namespace AdminModule\Forms\Acl;

use Nette\Utils\Neon;

class PresenterEdit extends \AdminModule\Forms\Form {

	public $destinationDir;
	public $presenterName;

	public function __construct($parent, $name, $presenterActions) {
		parent::__construct($parent, $name);

		$assertions = array(
			'deny' => 'No way!',
			'allow' => 'Yes',
			'assert1' => 'Test',
		);

		$roles = \Service\User\RoleList::getPairs('id', 'slug');

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
		$values = $this->getValues();

		$acl = Neon::encode(iterator_to_array($values), Neon::BLOCK);

		$resource = $this->presenterName;
		@mkdir($this->destinationDir, 0777);
		file_put_contents($this->destinationDir . '/' . $resource . '.neon', trim($acl));
	}

}
