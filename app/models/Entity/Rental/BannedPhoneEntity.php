<?php

namespace Entity\Rental;

use Doctrine\ORM\Mapping as ORM;
use Entity\Contact\Phone;

/**
 * Maju zakaz registracie
 * @ORM\Entity
 * @ORM\Table(name="rental_bannedphone")
 */
class BannedPhone extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Contact\Phone")
	 */
	protected $phone;

	/* ----------------------------- Methods ----------------------------- */

	/**
	 * @param \Entity\Contact\Phone $phone
	 *
	 * @return \Entity\Rental\BannedPhone
	 */
	public function setPhone(Phone $phone)
	{
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return \Entity\Contact\Phone
	 */
	public function getPhone()
	{
		return $this->phone;
	}

}
