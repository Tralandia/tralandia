<?php

namespace Tralandia\Phrase;

use Nette;


/**
 * @property \Tralandia\Phrase\Translation[] $translations m:belongsToMany
 */
class Phrase extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getTranslationsCount()
	{
		return count($this->translations);
	}

}
