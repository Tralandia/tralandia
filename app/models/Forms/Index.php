<?php

namespace Forms;

use Nette\Forms\Form,
	Nette\Application as NA,
	Nette\Diagnostics\Debugger;

class Index extends \CoolForm {
	
    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		$this->addDateTimePicker('published', 'Published');
		$this->addComboSelect('currency', 'Currency', $this->em->getRepository('Currency')->fetchPairs('id', 'code'))
			->setRequired('Currency must be filled');
		$this->addComboSelect('event', 'Event', $this->em->getRepository('Event')->fetchPairs('id', function($row) {
			return empty($row->unit) ? $row->name : "{$row->name} ({$row->unit})";
		}))
			->setRequired('Event must be filled')
			->setTranslator(null);
		$this->addText('actual', 'Actual Index')
			->addCondition(Form::FILLED)
				->addRule(Form::FLOAT, 'Actual Index must be numeric');
		$this->addText('forecast', 'Forecast Index')
			->addCondition(Form::FILLED)
				->addRule(Form::FLOAT, 'Forecast Index must be numeric');
		$this->addText('previous', 'Previous Index')
			->addCondition(Form::FILLED)
				->addRule(Form::FLOAT, 'Previous Index must be numeric');
		$this->addSelect('impact', 'Impact', array(0, 1, 2, 3));
		$this->addText('source', 'Source');
		$this->addText('measures', 'Measures');
		$this->addText('usualEffect', 'Usual Effect');
		$this->addText('frequency', 'Frequency');
		$this->addDateTimePicker('nextRelease', 'Next Release');
		$this->addText('ffNotes', 'FF Notes');
		$this->addText('whyTradersCare', 'Why Traders Care');
		$this->addText('alsoCalled', 'Also Called');
		$this->addText('acroExpand', 'Acro Expand');
			
		$this->addSubmit('save', 'Save');
		$this->onSuccess[] = callback($this, 'onSave');
	}
	
	public function onSave(Form $form) {
		$this->parent->invalidateControl();
		$values = $form->getValues();

		$event = $this->em->find('Event', $values->event);
		$currency = $this->em->find('Currency', $values->currency);

		try {
			$id = (int)$this->getParam('id');
			if ($id > 0) {
				$index = $this->em->find('Index', $id);
				$index->setData($values);
				$index->setCurrency($currency);
				$index->setEvent($event);
				$this->em->persist($index);
				$this->em->flush();
				
				$this->flashMessage('The index has been updated.');
				if (!$this->parent->isAjax()) $this->parent->redirect('this');
			} else {
				$index = new \Index;
				$index->setData($values);
				$index->setCurrency($currency);
				$index->setEvent($event);
				$this->em->persist($index);
				$this->em->flush();
				
				$this->flashMessage('The index has been added.');
				$this->parent->redirect('edit', $index->id);
			}
		} catch (PDOException $e) {
			Debugger::log($e->getMessage(), Debugger::ERROR);
			$this->flashMessage($e->getMessage(), 'error');
		}
    }
}