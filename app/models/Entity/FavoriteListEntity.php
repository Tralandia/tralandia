<?php

namespace Entity;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="favorite_list")
 *
 */
class FavoriteList extends \Entity\BaseEntity {


	/**
	 * @var datetime
	 * @ORM\Column(type="datetime")
	 */
	protected $lastUsed;

	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Entity\Rental\Rental")
	 * @ORM\JoinTable(name="_favorite_list_rental",
	 *      joinColumns={@ORM\JoinColumn(name="favorite_list_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="rental_id", referencedColumnName="id")}
	 *      )
	 */
	protected $rentals;

	/**
	 * @ORM\prePersist
	 * @param \DateTime $lastUsed
	 *
	 * @return FavoriteList
	 */
	public function setLastUsed(\DateTime $lastUsed = NULL){

		if($lastUsed) {
			$this->lastUsed = $lastUsed;
		}

		if(!$this->lastUsed) {
			$this->lastUsed = new \DateTime();
		}

		return $this;
	}


	public function addRentals($rentals)
	{
		foreach($rentals as $rental) $this->addRental($rental);
	}

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();

		$this->rentals = new \Doctrine\Common\Collections\ArrayCollection;
	}

	/**
	 * @return \DateTime|NULL
	 */
	public function getLastUsed()
	{
		return $this->lastUsed;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\FavoriteList
	 */
	public function addRental(\Entity\Rental\Rental $rental)
	{
		if(!$this->rentals->contains($rental)) {
			$this->rentals->add($rental);
		}

		return $this;
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\FavoriteList
	 */
	public function removeRental(\Entity\Rental\Rental $rental)
	{
		$this->rentals->removeElement($rental);

		return $this;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Entity\Rental\Rental[]
	 */
	public function getRentals()
	{
		return $this->rentals;
	}
}
