<?php

namespace Tralandia\Invoicing;

use Nette;


/**
 * @property string $slug
 * @property \Tralandia\Phrase\Phrase $name m:hasOne(name_id)
 * @property string $strtotime
 * @property integer $sort = 0
 * @property boolean $separatorAfter = FALSE
 */
class ServiceDuration extends \Tralandia\Lean\BaseEntity
{
	const DURATION_NO_DURATION = '_no_duration_';
	const DURATION_FOREVER = '_forever_';

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}


}
