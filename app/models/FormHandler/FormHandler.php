<?php

namespace FormHandler;

use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\SubmitButton;

abstract class FormHandler extends Nette\Object
{

	public function attach(Form $form)
	{
		$self = $this;
		if (method_exists($this, 'handleSuccess')) {
			$form->onSuccess[] = function (Form $form) use ($self) {
				try {
					$values = $form->getFormattedValues();
					$self->handleSuccess($values);

				} catch (ValidationError $e) {
					foreach ($e->errors as $error) {
						$control = $error['control'] ? $form->getComponent($error['control']) : $form;
						$control->addError($error['message']);
					}
				}
			};
		}
		if (method_exists($this, 'handleError')) {
			$form->onError[] = function (Form $form) use ($self) {
				$self->handleError($form->getValues());
			};
		}
		if (method_exists($this, 'handleSubmit')) {
			$form->onSubmit[] = function (Form $form) use ($self) {
				try {
					$validValues = $form->getValidFormattedValues();
					$values = $form->getFormattedValues();
					$self->handleSubmit($validValues, $values);

				} catch (ValidationError $e) {
					foreach ($e->errors as $error) {
						$control = $error['control'] ? $form->getComponent($error['control']) : $form;
						$control->addError($error['message']);
					}
				}
			};
		}
	}

	public function attachButton(SubmitButton $button)
	{
		$self = $this;
		if (method_exists($this, 'handleClick')) {
			$button->onClick[] = function (SubmitButton $button) use ($self) {
				$form = $button->form;
				try {
					$self->handleClick($form->getValues());

				} catch (ValidationError $e) {
					foreach ($e->errors as $error) {
						$control = $error['control'] ? $form->getComponent($error['control']) : $form;
						$control->addError($error['message']);
					}
				}
			};
		}
		if (method_exists($this, 'handleInvalidClick')) {
			$button->onInvalidClick[] = function (SubmitButton $button) use ($self) {
				$form = $button->form;
				$self->handleInvalidClick($form->getValues());
			};
		}
	}

}
