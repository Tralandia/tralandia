<?php

namespace Extras\Forms\Rendering;

use Nette,
	Nette\Utils\Html,
	Nette\Forms\Rendering\DefaultFormRenderer;

class AdminFormRenderer extends DefaultFormRenderer {

	const ERROR_CLASS = 'error';

	public function renderPair(Nette\Forms\IControl $control)
	{
		$pair = $this->getWrapper('pair container');
		$pair->add($this->renderLabel($control));
		$pair->add($this->renderControl($control));
		$pair->class($this->getValue($control->isRequired() ? 'pair .required' : 'pair .optional'), TRUE);
		$pair->class($control->getOption('class'), TRUE);

		if ($control->hasErrors()) {
			$pair->addClass(self::ERROR_CLASS);
		}

		if (++$this->counter % 2) {
			$pair->class($this->getValue('pair .odd'), TRUE);
		}
		$pair->id = $control->getOption('id');
		$pair = $pair->render(0);

		return $control->getOption('renderBefore').$pair.$control->getOption('renderAfter');
	}


	public function renderPairMulti(array $controls)
	{
		$s = array();
		$renderBefore = NULL;
		$isButtonsPair = NULL;
		foreach ($controls as $control) {
			if (!$control instanceof Nette\Forms\IControl) {
				throw new Nette\InvalidArgumentException("Argument must be array of IFormControl instances.");
			}
			if($isButtonsPair === NULL && $control instanceof Nette\Forms\Controls\Button) {
				$isButtonsPair = TRUE;
				$renderBefore = $control->getOption('renderBefore');
			}
			$s[] = (string) $control->getControl();
		}
		$pair = $this->getWrapper('pair container');
		$pair->add($this->renderLabel($control));
		if($isButtonsPair) {
			$pair->add(Html::el('div')->addClass('form-actions')->add($this->getWrapper('control container')->setHtml(implode(" ", $s))))->addClass('span12');
		} else {
			$pair->add($this->getWrapper('control container')->setHtml(implode(" ", $s)));
		}
		return $renderBefore.$pair->render(0);
	}
}
