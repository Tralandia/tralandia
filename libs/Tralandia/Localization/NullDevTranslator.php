<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 23/06/14 14:30
 */

namespace Tralandia\Localization;


use Nette;

class NullDevTranslator implements \Nette\Localization\ITranslator
{

	public function __construct()
	{

	}


	/**
	 * Translates the given string.
	 *
	 * @param  string   message
	 * @param  int      plural count
	 *
	 * @return string
	 */
	function translate($message, $count = NULL)
	{
		return $message;
	}

}
