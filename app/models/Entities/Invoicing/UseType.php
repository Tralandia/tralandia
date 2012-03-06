<?php

namespace Entities\Invoicing;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_use")
 */
class UseType extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Dictionary\Phrase")
	 */
	protected $name;

}