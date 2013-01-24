<?php

namespace Extras\Forms\Rendering;

use Nette,
	Nette\Utils\Html,
	Nette\Forms\Rendering\DefaultFormRenderer;

class DefaultRenderer extends DefaultFormRenderer {

	public $wrappers = array(
		'form' => array(
			'container' => NULL,
		),

		'error' => array(
			'container' => 'ul class=error',
			'item' => 'li',
		),

		'group' => array(
			'container' => 'fieldset',
			'label' => 'legend',
			'description' => 'p',
		),

		'controls' => array(
			'container' => 'div',
		),

		'pair' => array(
			'container' => 'div class=control-group',
			'.required' => 'required',
			'.optional' => NULL,
			'.odd' => NULL,
		),

		'control' => array(
			'container' => 'div class=controls',
			'.odd' => NULL,

			'description' => 'small',
			'requiredsuffix' => '',
			'validationcontainer' => 'i class="va­lidation entypo-valid"',
			'errorcontainer' => 'div class=control-error',
			'erroritem' => 'span',

			'.required' => 'required',
			'.text' => 'text',
			'.password' => 'text',
			'.file' => 'text',
			'.submit' => 'btn btn-orange',
			'.image' => 'imagebutton',
			'.button' => 'btn btn-orange',
		),

		'label' => array(
			'container' => 'div class=form-label',
			'label' => 'label',
			'suffix' => NULL,
			'requiredsuffix' => '',
			'description' => 'small',
		),

		'hidden' => array(
			'container' => 'div',
		),
	);

	public function init() {
		$this->form->getElementPrototype()->addClass('traform')->addClass('dashboard');
		parent::init();
	}


	public function renderPair(Nette\Forms\IControl $control)
	{
		$pair = $this->getWrapper('pair container');
		$pair->add($this->renderLabel($control));
		$pair->add($this->renderControl($control));
		$pair->class($this->getValue($control->isRequired() ? 'pair .required' : 'pair .optional'), TRUE);
		$pair->class($control->getOption('class'), TRUE);
		if (++$this->counter % 2) {
			$pair->class($this->getValue('pair .odd'), TRUE);
		}
		$pair->id = $control->getOption('id');
		return $pair->render(0);
	}

	public function renderLabel(Nette\Forms\IControl $control)
	{
		$head = $this->getWrapper('label container');

		if ($control instanceof Nette\Forms\Controls\Checkbox || $control instanceof Nette\Forms\Controls\Button) {
			return $head->setHtml(($head->getName() === 'td' || $head->getName() === 'th') ? '&nbsp;' : '');

		} else {
			$wrapper = $control->getLabel();
			$suffix = $this->getValue('label suffix') . ($control->isRequired() ? $this->getValue('label requiredsuffix') : '');
			if ($wrapper instanceof Html) {
				$wrapper->setHtml($wrapper->getHtml() . $suffix);
				$suffix = '';
			}

			$description = $control->getOption('description');
			if ($description instanceof Html) {
				$description = ' ' . $control->getOption('description');

			} elseif (is_string($description)) {
				$description = ' ' . $this->getWrapper('label description')->setText($control->translate($description));

			} else {
				$description = '';
			}

			return $head->setHtml((string) $wrapper . $description . $suffix);
		}
	}

	public function renderValidation(Nette\Forms\IControl $control)
	{
		return $this->getWrapper('control validationcontainer');
	}

	public function renderError(Nette\Forms\IControl $control = NULL)
	{
		$errors = $control ? $control->getErrors() : $this->form->getErrors();
		$container = $this->getWrapper($control ? 'control errorcontainer' : 'error container');
		$item = $this->getWrapper($control ? 'control erroritem' : 'error item');

		foreach ($errors as $error) {
			$item = clone $item;
			if ($error instanceof Html) {
				$item->add($error);
			} else {
				$item->setText($error);
			}
			$container->add($item);
		}
		return "\n" . $container->render($control ? 1 : 0);
	}

	public function renderControl(Nette\Forms\IControl $control)
	{
		$body = $this->getWrapper('control container');
		if ($this->counter % 2) {
			$body->class($this->getValue('control .odd'), TRUE);
		}

		$validation = $this->renderValidation($control);
		$error = $this->renderError($control);

		if ($control instanceof Nette\Forms\Controls\Checkbox || $control instanceof Nette\Forms\Controls\Button) {
			return $body->setHtml((string) $control->getControl() . (string) $control->getLabel());

		} else {
			return $body->setHtml((string) $control->getControl() . $validation . $error);
		}
	}
}
