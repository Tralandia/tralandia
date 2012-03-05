<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="RentalRepository")
 * @ORM\Primary(key="id", value="nameUrl")
 */
class Rental extends BaseEntity {
	
	const STATUS_NONE = NULL;
	const STATUS_CHECKED = 'checked';
	const STATUS_LIVE = 'live';

	/**
	 * @ORM\Column(type="string", nullable=true)
	 * @UIControl(type="select", options="STATUS_NONE:Nič, STATUS_CHECKED:Checked, STATUS_LIVE:Live")
	 */
	protected $status = self::STATUS_NONE;

	/**
	 * @ORM\Column(type="string")
	 * @UIControl(type="text", label="Name url")
	 */
	protected $nameUrl;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="rentals")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $user;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Country", fetch="EAGER")
	 * @UIControl(type="select", callback="getList")
	 */
	protected $country;
}
