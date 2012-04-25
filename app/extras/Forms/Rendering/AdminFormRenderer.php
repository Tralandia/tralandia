<?php

namespace Extras\Forms\Rendering;

use Nette,
	Nette\Utils\Html,
	Nette\Forms\Rendering\DefaultFormRenderer;

class AdminFormRenderer extends DefaultFormRenderer {

	public function renderPair(Nette\Forms\IControl $control)
	{
		$pair = parent::renderPair($control);
		return $control->getOption('renderBefore').$pair.$control->getOption('renderAfter');
	}


	public function renderPairMulti(array $controls)
	{
		$s = array();
		$addClass = NULL;
		foreach ($controls as $control) {
			if (!$control instanceof Nette\Forms\IControl) {
				throw new Nette\InvalidArgumentException("Argument must be array of IFormControl instances.");
			}
			if($control instanceof Nette\Forms\Controls\Button) {
				$addClass = 'pull-right';
			}
			$s[] = (string) $control->getControl();
		}
		$pair = $this->getWrapper('pair container');
		$pair->add($this->renderLabel($control));
		$pair->add($this->getWrapper('control container')->setHtml(implode(" ", $s)));
		$pair->addClass($addClass);
		return $pair->render(0);
	}


}
