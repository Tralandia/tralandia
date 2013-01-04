<?php

abstract class FormHandler extends Nette\Object
{
	public function attach(Form $form)
	{
		$self = $this;
		if (method_exists($this, 'handleSuccess')) {
			$form->onSuccess[] = function (Form $form) use ($self) {
				try {
					$self->handleSuccess($form->getValues());

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
	}

	public function attachButton(SubmitButton $button)
	{
		$self = $this;
		if (method_exists($this, 'handleClick')) {
			$button->onClick[] = function (SubmitButton $button) use ($self) {
				$form = $button->form;
				try {
					$self->handleClick($button, $form->getValues());

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
				$self->handleInvalidClick($button, $form->getValues());
			};
		}
	}
}

class RegistrationHandler extends FormHandler
{
	public $onFoo = array();
	public $onBar = array();

	public function handleSuccess($values)
	{
		$error = new ValidationError;

		if ($values->name != "Valid") {
			$error->addError("Name is invalid", 'name');
		}

		$error->assertValid();
		$this->model->save($values);
	}
}


class ValidationError extends \Exception
{
	public $errors = array();

	public function addError($error, $controlName = NULL)
	{
		$this->errors[] = array('message' => $error, 'control' => $controlName);
		return $this;
	}

	public function assertValid()
	{
		if ($this->errors) { throw $this; }
	}
}

$form = new Form;
$handler = new RegistrationHandler();
$handler->attach($form);
$handler->attachButton($form->addSubmit('send', "Odeslat"));
$handler->attachButton($form->addSubmit('send2', "Odeslat 2"));