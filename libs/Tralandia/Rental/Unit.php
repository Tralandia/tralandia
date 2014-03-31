<?php

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 * @property string $name
 * @property int $maxCapacity
 * @property \Tralandia\Rental\Rental $rental m:hasOne
 *
 * @method setRental(\Tralandia\Rental\Rental $rental)
 * @method setName()
 * @method setMaxCapacity()
 */
class Unit extends \Tralandia\Lean\BaseEntity
{


}
