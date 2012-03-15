<?php

namespace Entities\User;

use Entities\Contact;
use Entities\Dictionary;
use Entities\Location;
use Entities\Rental;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_user")
 */
class User extends BaseEntity {

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $login;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $password;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Role", mappedBy="users")
	 */
	protected $roles;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Contact\Contact", mappedBy="user")
	 */
	protected $contact;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Dictionary\Language")
	 */
	protected $languageDefault;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Location\Location", mappedBy="users")
	 */
	protected $locations;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entities\Rental\Type", mappedBy="users")
	 */
	protected $rentalTypes;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingSalutation;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingFirstName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingLastName;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyName;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $invoicingEmail;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $invoicingPhone;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entities\Contact\Contact")
	 */
	protected $invoicingUrl;

	/**
	 * @var address
	 * @ORM\Column(type="address", nullable=true)
	 */
	protected $invoicingAddress;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyId;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $invoicingCompanyVatId;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="User")
	 */
	protected $currentTelmarkOperator;

	/**
	 * @var json
	 * @ORM\Column(type="json")
	 */
	protected $attributes;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Combination", mappedBy="user")
	 */
	protected $combinations;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entities\Rental\Rental", mappedBy="user")
	 */
	protected $rentals;

}