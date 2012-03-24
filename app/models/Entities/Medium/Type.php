<?php

namespace Entities\Medium;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_type")
 */
class Type extends \Entities\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

}