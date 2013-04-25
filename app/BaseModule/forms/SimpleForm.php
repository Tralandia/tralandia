<?php

namespace BaseModule\Forms;

class SimpleForm extends BaseForm {


	public function buildForm()
	{

	}

	public function setDefaultsValues()
	{

	}

}

interface ISimpleFormFactory {

	/**
	 * @return SimpleForm
	 */
	public function create();
}
