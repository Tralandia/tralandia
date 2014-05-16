<?php

namespace Entity\Phrase;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="phrase_alias")
 */
class Alias extends \Entity\BaseEntity {

	/**
	 * @var Phrase
	 * @ORM\ManyToOne(targetEntity="Phrase", inversedBy="translations")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $phrase;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $help;

}
