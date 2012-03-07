<?php

namespace Entities\Dictionary;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dictionary_phrase")
 */
class Phrase extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToMany(type="Translation", mappedBy="phrase")
	 */
	protected $translations;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $ready = FALSE;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(type="Type")
	 */
	protected $type;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $entityId;

}