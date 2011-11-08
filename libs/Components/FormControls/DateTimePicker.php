<?php


require_once LIBS_DIR . '/Nette/Forms/Controls/TextInput.php';


/**
 * DatePicker input control.
 *
 * @author     Tomáš Kraina, Roman Sklenář
 * @package    Nette\Extras
 */
class DateTimePicker extends Nette\Forms\Controls\TextInput
{

	/**
	 * @param  string  label
	 * @param  int  width of the control
	 * @param  int  maximum number of characters the user may enter
	 */
	public function __construct($label, $cols = NULL, $maxLenght = NULL)
	{
		parent::__construct($label, $cols, $maxLenght);
	}


	/**
	 * Returns control's value.
	 * @return mixed
	 */
	public function getValue()
	{
		$this->value = trim($this->value);
		if (strlen($this->value)) {
			if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})$/', $this->value)) {
				return \DateTime::createFromFormat("d.m.Y H:i", $this->value);
			}
			return \DateTime::createFromFormat("d.m.Y H:i:s", $this->value . " 00:00:00");
		}
		return $this->value;
	}


	/**
	 * Sets control's value.
	 * @param  string
	 * @return void
	 */
	public function setValue($value)
	{
		if ($value instanceof \DateTime) {
			$value = $value->format('d.m.Y H:i');
		} else {
			$value = preg_replace('~([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})~', '$3.$2.$1 $4:$5', trim($value));
		}
		parent::setValue($value);
	}


	/**
	 * Generates control's HTML element.
	 * @return Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->class = 'datetimepicker';

		return $control;
	}

}
