<?php

namespace Entity\Seo;

use Entity\Phrase;
use Entity\Location;
use Entity\Medium;
use Entity\Rental;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="seo_backlink", indexes={@ORM\Index(name="rental", columns={"rental_id"})})
 *
 */
class BackLink extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	protected $rental;

	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $url;

	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Seo\BackLink
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetRental()
	{
		$this->rental = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Rental|NULL
	 */
	public function getRental()
	{
		return $this->rental;
	}

	/**
	 * @param string
	 * @return \Entity\Seo\BackLink
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * @return \Entity\Seo\BackLink
	 */
	public function unsetUrl()
	{
		$this->url = NULL;

		return $this;
	}

	/**
	 * @return string|NULL
	 */
	public function getUrl()
	{
		return $this->url;
	}
}
