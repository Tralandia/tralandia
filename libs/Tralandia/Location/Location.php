<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Location;

use Nette;


/**
 * @property string $localName
 * @property \Tralandia\Phrase\Phrase $name m:hasOne(name_id)
 * @property \Tralandia\Language\Language $defaultLanguage m:hasOne(defaultLanguage_id:)
 * @property \Tralandia\Currency $defaultCurrency m:hasOne(defaultCurrency_id:)
 * @property \Tralandia\Domain\Domain $domain m:hasOne
 */
class Location extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}

}
