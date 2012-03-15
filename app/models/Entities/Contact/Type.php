<?php

namespace Entities\Contact;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact_type")
 */
class Type extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $class;

}