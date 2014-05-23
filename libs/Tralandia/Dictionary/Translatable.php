<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 14/05/14 09:51
 */

namespace Tralandia\Dictionary;


use Nette;
use Tralandia\Localization\Translator;

class Translatable
{

	const TYPE_STRING = 'string';
	const TYPE_PHRASE = 'phrase';

	/**
	 * @var array
	 */
	protected $sentence = [];


	/**
	 * @param $string
	 *
	 * @return Translatable
	 */
	public function string($string)
	{
		return $this->add(self::TYPE_STRING, $string);
	}


	/**
	 * @param $phrase
	 *
	 * @return Translatable
	 */
	public function phrase($phrase)
	{
		return $this->add(self::TYPE_PHRASE, $phrase);
	}


	/**
	 * @param $type
	 * @param $value
	 *
	 * @return Translatable
	 */
	public function add($type, $value)
	{
		$this->sentence[] = [
			'type' => $type,
			'value' => $value,
		];
		return $this;
	}


	/**
	 * @param Translator $translator
	 *
	 * @return string
	 */
	public function translate(Translator $translator)
	{
		$string = '';
		foreach($this->sentence as $key => $value) {
			if($value['type'] == self::TYPE_PHRASE) {
				$value['value'] = call_user_func_array([$translator, 'translate'], $value['value']);
			}

			$string .= $value['value'];
		}

		return $string;
	}


	/**
	 * @return Translatable
	 */
	public static function from()
	{
		$t = new self;
		$args = func_get_args();
		foreach($args as $arg) {
			if(is_scalar($arg)) {
				$t->string($arg);
			} else if(is_array($arg)) {
				$t->phrase($arg);
			}
		}

		return $t;
	}
}
