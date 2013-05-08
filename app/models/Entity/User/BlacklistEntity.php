<?php

namespace Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_blacklist", indexes={@ORM\index(name="email", columns={"email"})})
*/
class Blacklist extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;

	//@entity-generator-code --- NEMAZAT !!!
}