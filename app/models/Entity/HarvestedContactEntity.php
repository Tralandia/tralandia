<?php

namespace Entity;

use Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="harvester_contacts", indexes={@ORM\Index(name="value", columns={"value"}), @ORM\Index(name="type", columns={"type"})})
 *
 */
class HarvestedContact extends \Entity\BaseEntity
{

	const TYPE_EMAIL = 'email';
	const TYPE_PHONE = 'phone';

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $value;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="\Entity\Rental\Rental")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

}
