<?php

namespace Entity\Contact;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact_potentialMember",
 * 		indexes={
 * 			@ORM\Index(name="emailSent", columns={"emailSent"})
 * 		})
 *
 * @method getEmail()
 * @method getLanguage()
 * @method getPrimaryLocation()
 */
class PotentialMember extends \Entity\BaseEntity
{

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $email;


	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $emailSent = FALSE;


	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $unsubscribed = FALSE;


	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Language")
	 */
	protected $language;


	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Location\Location")
	 */
	protected $primaryLocation;


}
