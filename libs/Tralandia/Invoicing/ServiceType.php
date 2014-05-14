<?php

namespace Tralandia\Invoicing;

use Nette;


/**
 * @property string $slug
 * @property \Tralandia\Phrase\Phrase $name m:hasOne(name_id)
 */
class ServiceType extends \Tralandia\Lean\BaseEntity
{


	public function getNameId()
	{
		return $this->row->name_id;
	}

}
