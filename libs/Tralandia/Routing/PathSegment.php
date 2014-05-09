<?php

namespace Tralandia\Routing;

use Nette;


/**
 * @property string $pathSegment
 */
class PathSegment extends \Tralandia\Lean\BaseEntity
{
	const TYPE_PAGE = 2;
	const TYPE_ATTRACTION_TYPE = 4;
	const TYPE_LOCATION = 6;
	const TYPE_RENTAL_TYPE = 8;

}
