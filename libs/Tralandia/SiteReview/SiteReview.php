<?php

namespace Tralandia\SiteReview;

use Nette;


/**
 * @property int $id
 * @property \Tralandia\Language\Language $language m:hasOne
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id:)
 * @property \Tralandia\Rental\Rental|NULL $rental m:hasOne
 * @property string|NULL $senderEmail
 * @property string|NULL $senderName
 * @property string|NULL $testimonial
 * @property int|NULL $status
 */
class SiteReview extends \Tralandia\Lean\BaseEntity
{


}
