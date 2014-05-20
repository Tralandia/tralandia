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
 */
class Configuration extends \Entity\BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $url;


}
