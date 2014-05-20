<?php

namespace Tralandia\PersonalSite;

use Nette;


/**
 * @property string $template m:enum(self::TEMPLATE_*)
 */
class Configuration extends \Tralandia\Lean\BaseEntity
{

	const TEMPLATE_FIRST = 'first';
	const TEMPLATE_SECOND = 'second';


}
