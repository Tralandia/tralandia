<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

use Extras\Annotation as EA;
use Extras\FileStorage;

use Nette\Http\FileUpload;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_video", indexes={@ORM\Index(name="sort", columns={"sort"})})
 *
 * @method setService()
 * @method string getService()
 * @method setVideoId()
 * @method string getVideoId()
 */
class Video extends \Entity\BaseEntity
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Rental", inversedBy="videos")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $service;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $videoId;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort = 0;


	public function getUrl()
	{
		return \Tralandia\Rental\Video::getUrlFor($this->service, $this->videoId);
	}


	public function getEmbedCode($width = 500, $height = 281)
	{
		return \Tralandia\Rental\Video::getEmbedCodeFor($this->service, $this->videoId, $width, $height);
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\Image
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Image
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
	 * @param integer
	 * @return \Entity\Rental\Image
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}

	/**
	 * @return \Entity\Rental\Image
	 */
	public function unsetSort()
	{
		$this->sort = NULL;

		return $this;
	}

	/**
	 * @return integer|NULL
	 */
	public function getSort()
	{
		return $this->sort;
	}
}
