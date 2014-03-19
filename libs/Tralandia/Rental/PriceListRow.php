<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 * @property int $roomCount
 * @property int $bedCount
 * @property int $extraBedCount
 * @property int $price
 * @property \Tralandia\Rental\RoomType $roomType m:hasOne(roomType_id:)
 */
class PriceListRow extends \Tralandia\Lean\BaseEntity
{


}
