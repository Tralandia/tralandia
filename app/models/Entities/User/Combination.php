<?php

namespace Entities\User;

use Entities\Dictionary;
use Entities\Location;
use Entities\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_combination")
 */
class Combination extends \BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $user;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Location\Location")
	 */
	protected $country;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $language;

	/**
	 * @var Collection
	 * @ORM\Column(type="integer")
	 */
	protected $languageLevel;

}