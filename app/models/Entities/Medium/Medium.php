<?php

namespace Entities\Medium;

use Entities\Dictionary;
use Entities\Attraction;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="medium_medium")
 */
class Medium extends BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Type")
	 */
	protected $type;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entities\Dictionary\Phrase")
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Attraction\Attraction", inversedBy="media")
	 */
	protected $attraction;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Rental\Rental", inversedBy="media")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Seo\SeoUrl", inversedBy="media")
	 */
	protected $seoUrl;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Ticket\Message", inversedBy="media")
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