<?php

namespace Tra\Forms;

class Rental extends BaseForm {
	
    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->addSelect('country', 'Country', $this->em->getRepository('Country')->fetchPairs('id', 'iso'))
			->setRequired('Country must be filled');
		$this->addText('user', 'User')
			->setRequired('User must be filled');
		$this->addText('nameUrl', 'Name Url');
		$this->addSelect('status', 'Status', array(
			Tra\Entities\Rental::STATUS_NONE => 'None',
			Tra\Entities\Rental::STATUS_CHECKED => 'Checked',
			Tra\Entities\Rental::STATUS_LIVE => 'Live'
		))->setRequired('Status must be filled');
		$this->addSubmit('save', 'Save');
		
		
		$this->onSuccess[] = callback($this, 'onSave');
	}

	public function onSave(Form $form) {
		$values = $form->getValues();
	
		try {
			$id = (int)$this->getParam('id');
			if ($id > 0) {
				$rental = new Tra\Service\Rental($id);
				//$rental->prepareData($values);
				$rental->update($values);
				$rental->changeStatus($values->status);
				$this->flashMessage('The rental has been updated.');
			} else {
				$rental = new Tra\Service\Rental;
				//$rental->prepareData($values);
				$rental->create($values);
				$rental->sendMail();
				$this->flashMessage('The rental has been created.');
			}
		} catch (Exception $e) {
			$this->flashMessage('ERROR');
		}
	}
}