<?php

namespace Extras\Forms\Controls;


use Nette\Forms\Container;


class AdvancedDatePicker extends \Nextras\Forms\Controls\DatePicker {

	/**
	 * Class constructor.
	 *
	 * @param  string
	 */
	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'text';
	}


}
