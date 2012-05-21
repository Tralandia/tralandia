<?php

namespace AdminModule\Forms\Acl;

use Nette\Utils\Neon;

class EntityEdit extends \AdminModule\Forms\Form {

	public $destinationDir;
	public $entityName;

	public function __construct($parent, $name, $entityActions, $roles) {
		parent::__construct($parent, $name);

		$assertions = array(
			'deny' => 'No way!',
			'allow' => 'Yes',
			'assert1' => 'Test',
		);

		foreach ($entityActions as $key => $value) {
			$edit = $this->addContainer($value->name.'_show');
			$show = $this->addContainer($value->name.'_edit');
			foreach ($roles as $roleId => $role) {
				$edit->addSelect($role, $role, $assertions)
					->getControlPrototype()->addClass('input-small');
				$show->addSelect($role, $role, $assertions)
					->getControlPrototype()->addClass('input-small');
			}
		}

		$this->addSubmit('submit', 'OK maan');
		
		$this->onSuccess[] = callback($this, 'onSuccess');
	}

	public function onSuccess(EntityEdit $form) {
		$values = $this->getValues(TRUE);
		debug($values);
		$acl = Neon::encode($values, Neon::BLOCK);

		$resource = $this->entityName;
		@mkdir($this->destinationDir, 0777);
		file_put_contents($this->destinationDir . '/' . $resource . '.neon', trim($acl));
	}

}
