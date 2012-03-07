<?php

namespace Entities\Rental;

use Entities\Dictionary;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_fulltext")
 */
class Fulltext extends \BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(type="Rental", inversedBy="fulltexts")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $value;

}