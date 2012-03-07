<?php

namespace Entities\Seo;

use Entities\Dictionary;
use Entities\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_titlesuffix")
 */
class TitleSuffix extends \BaseEntity {

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
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $suffix;

}