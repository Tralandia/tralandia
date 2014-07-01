<?php

namespace Tralandia\PersonalSite;

use Nette;


/**
 * @property string $template m:enum(self::TEMPLATE_*)
 * @property string|null $gaCode
 * @property string|null $conversionOnReservation
 */
class Configuration extends \Tralandia\Lean\BaseEntity
{

	const TEMPLATE_FIRST = 'first';
	const TEMPLATE_SECOND = 'second';


}
