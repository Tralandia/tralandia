<?php

namespace Entity\Page;

use Entity\Dictionary;
use Entity\Attraction;
use Doctrine\ORM\Mapping as ORM;
use	Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="page_page")
 * @EA\Service(name="\Service\Page\Page")
 * @EA\ServiceList(name="\Service\Page\PageList")
 * @EA\Primary(key="id", value="name")
 */
class Page extends \Entity\BaseEntityDetails {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $title;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $heading;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Dictionary\Phrase", cascade={"persist", "remove"})
	 */
	protected $description;

	/**
	 * @var text
	 * @ORM\Column(type="string")
	 */
	protected $destination;


//@entity-generator-code <--- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct() {
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Page\Page
	 */
	public function setName(\Entity\Dictionary\Phrase $name) {
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getName() {
		return $this->name;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Page\Page
	 */
	public function setTitle(\Entity\Dictionary\Phrase $title) {
		$this->title = $title;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getTitle() {
		return $this->title;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Page\Page
	 */
	public function setHeading(\Entity\Dictionary\Phrase $heading) {
		$this->heading = $heading;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getHeading() {
		return $this->heading;
	}
		
	/**
	 * @param \Entity\Dictionary\Phrase
	 * @return \Entity\Page\Page
	 */
	public function setDescription(\Entity\Dictionary\Phrase $description) {
		$this->description = $description;

		return $this;
	}
		
	/**
	 * @return \Entity\Dictionary\Phrase|NULL
	 */
	public function getDescription() {
		return $this->description;
	}
		
	/**
	 * @param string
	 * @return \Entity\Page\Page
	 */
	public function setDestination($destination) {
		$this->destination = $destination;

		return $this;
	}
		
	/**
	 * @return string|NULL
	 */
	public function getDestination() {
		return $this->destination;
	}
}