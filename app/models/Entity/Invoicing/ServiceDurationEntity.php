<?php

namespace Entity\Invoicing;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="invoicing_serviceduration")
 * @method setSlug($slug)
 * @method getSlug()
 * @method setName(\Entity\Phrase\Phrase $name)
 * @method \Entity\Phrase\Phrase getName()
 * @method setStrtotime($strtotime)
 * @method getStrtotime()
 * @method setSort($sort)
 * @method getSort()
 * @method setSeparatorAfter($separatorAfter)
 * @method getSeparatorAfter()
 */
class ServiceDuration extends \Entity\BaseEntity {

	const DURATION_NO_DURATION = '_no_duration_';
	const DURATION_FOREVER = '_forever_';


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

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $strtotime;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort = 0;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $separatorAfter = FALSE;

}
