<?php

namespace AdminModule\Components;

use AdminModule\Components\BaseGridControl;
use Nette\Reflection\ClassType;

class AdminGridControl extends BaseGridControl {

	/**
	 * @var \Extras\Translator
	 */
	public $translator;

	/**
	 * @var \Environment\Collator
	 */
	public $collator;

	/**
	 * @var TRUE|FALSE
	 */
	public $showActions = true;

	public function __construct(\Extras\Translator $translator, \Environment\Collator $collator)
	{
		$this->translator = $translator;
		$this->collator = $collator;
		parent::__construct();
	}


	/**
	 * @param $entities
	 * @param $by
	 *
	 * @return array
	 */
	public function getTranslatedAndOrderedBy($entities, $by)
	{
		$order = [];
		foreach ($entities as $key => $entity) {
			if (isset($entity->{$by})) {
				$name = $this->translator->translate($entity->{$by});
				$order[$name . $key] = $key;
			} else {
				$order[$key] = $key;
			}
		}

		$this->collator->ksort($order);

		$return = [];
		foreach ($order as $k => $v) {
			$return[$v] = $entities[$v];
		}
		return $return;
	}

	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);

		$template->setFile(__DIR__ . '/adminGridControl.latte'); // automatické nastavení šablony

		return $template;
	}

	/**
	 * @return array
	 */
	protected function getCellsTemplatesFiles()
	{
		$files = array();
		$files[] = dirname(ClassType::from($this)->getFileName()) . '/' . lcfirst( ClassType::from($this)->getShortName() ) . '.latte';
		$files[] = __DIR__ . '/@adminGridCellsTemplate.latte';
		if ($this->showActions) {
			$files[] = __DIR__ . '/@adminGridActionsTemplate.latte';
		}
		$files = array_merge($files, parent::getCellsTemplatesFiles());
		return $files;
	}

}
