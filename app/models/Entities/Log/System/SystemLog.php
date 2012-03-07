<?php

namespace Entities\Log\System;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="log_system_systemlog")
 */
class SystemLog extends \BaseEntityDetails {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;

	/**
	 * @var text
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $comment;

}