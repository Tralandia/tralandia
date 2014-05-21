<?php

namespace Tralandia\Routing;

use Nette;


/**
 * @property string $hash
 * @property string $destination
 * @property \Tralandia\Phrase\Phrase $titlePattern m:hasOne(titlePattern_id)
 * @property \Tralandia\Phrase\Phrase $h1Pattern m:hasOne(h1Pattern_id)
 * @property \Tralandia\Phrase\Phrase $genericContent m:hasOne(genericContent_id)
 */
class Page extends \Tralandia\Lean\BaseEntity
{

}
