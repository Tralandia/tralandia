<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_servicetype")
 *
 * @method getSlug()
 * @method setSlug($slug)
 * @method \Entity\Phrase\Phrase getName()
 * @method setName(\Entity\Phrase\Phrase $phrase)
 */
class ServiceType extends \Entity\BaseEntity {

	const SLUG_FEATURED = 'featured';
	const SLUG_PERSONAL_SITE = 'personalSite';


	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @var string
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

}
