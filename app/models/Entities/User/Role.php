<?php

namespace Entities\User;

use Entities\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_role")
 */
class Role extends \Entities\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var url
	 * @ORM\Column(type="url")
	 */
	protected $homePage;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\User\User", inversedBy="roles")
	 */
	protected $users;

}