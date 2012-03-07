<?php

namespace Entities\Rental;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_type")
 */
class Type extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

}
