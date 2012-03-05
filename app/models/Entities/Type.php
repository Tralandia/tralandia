<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Type extends \BaseEntity
{
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;
}
