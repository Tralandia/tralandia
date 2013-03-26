<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_interviewquestion")
 * @EA\Primary(key="id", value="id")
 */
class InterviewQuestion extends \Entity\BaseEntityDetails
{

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $question;

	/**
	 * @var integer
	 * @ORM\Column(type="integer")
	 */
	protected $sort = 0;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * @param \Entity\Phrase\Phrase
	 *
	 * @return \Entity\Rental\InterviewQuestion
	 */
	public function setQuestion(\Entity\Phrase\Phrase $question)
	{
		$this->question = $question;

		return $this;
	}


	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getQuestion()
	{
		return $this->question;
	}


	/**
	 * @param integer
	 *
	 * @return \Entity\Rental\InterviewQuestion
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

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
