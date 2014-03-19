<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Language;

use Nette;


/**
 * @property int $id
 * @property string $iso
 */
class Language extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return mixed
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}



}
