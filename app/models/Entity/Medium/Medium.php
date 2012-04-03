<?php

namespace Entity\Medium;

use Entity\Dictionary;
use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_medium")
 */
class Medium extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Attraction\Attraction", inversedBy="media")
	 */
	protected $attraction;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="media")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Seo\SeoUrl", inversedBy="media")
	 */
	protected $seoUrl;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Ticket\Message", inversedBy="media")
	 */
	protected $message;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $uri;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort;

}