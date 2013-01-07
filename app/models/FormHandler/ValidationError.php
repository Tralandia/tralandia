<?php

namespace FormHandler;

class ValidationError extends \Exception
{
	public $errors = array();

	public function addError($error, $controlName = NULL)
	{
		$this->errors[] = array('message' => $error, 'control' => $controlName);
		return $this;
	}

	public function throwError($error, $controlName = NULL)
	{
		$this->addError($error, $controlName);
		$this->assertValid();
	}

	public function assertValid()
	{
		if ($this->errors) { throw $this; }
	}
}