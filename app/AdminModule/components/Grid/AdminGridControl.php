<?php

namespace AdminModule\Components;

use AdminModule\Components\BaseGridControl;
use Nette\Reflection\ClassType;

class AdminGridControl extends BaseGridControl {

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
		$files = array_merge($files, parent::getCellsTemplatesFiles());
		return $files;
	}

}
