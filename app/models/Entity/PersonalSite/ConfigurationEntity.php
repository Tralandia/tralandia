<?php

namespace Entity\PersonalSite;

use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="personalsite_configuration",
 * 		indexes={
 * 			@ORM\Index(name="url", columns={"url"})
 * 		})
 *
 * @method string getUrl()
 * @method \Entity\Rental\Rental getRental()
 */
class Configuration extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $url;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $template;


	/**
	 * @var \Entity\Rental\Rental
	 * @ORM\OneToOne(targetEntity="Entity\Rental\Rental", mappedBy="personalSiteConfiguration")
	 */
	protected $rental;



}
