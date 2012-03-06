<?php

namespace Entities\Emailing;

use Entities\Emailing;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="emailing_batch")
 */
class Batch extends \BaseEntityDetails {

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $confirmed;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(type="Email", inversedBy="batches")
	 */
	protected $email;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $totalCount;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $subject;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $body;

	/**
	 * @var text
	 * @ORM\Column(type="text")
	 */
	protected $bodyHtml;

}