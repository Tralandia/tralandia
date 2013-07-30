<?php

namespace Entity\Rental;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;
use    Extras\Annotation as EA;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rental_interviewanswer")
 * @EA\Primary(key="id", value="id")
 */
class InterviewAnswer extends \Entity\BaseEntityDetails
{

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\Rental", inversedBy="interviewAnswers")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $rental;

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Rental\InterviewQuestion")
	 */
	protected $question;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $answer;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param \Entity\Rental\Rental
	 * @return \Entity\Rental\InterviewAnswer
	 */
	public function setRental(\Entity\Rental\Rental $rental)
	{
		$this->rental = $rental;

		return $this;
	}

	/**
	 * @return \Entity\Rental\InterviewAnswer
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
	 * @param \Entity\Rental\InterviewQuestion
	 * @return \Entity\Rental\InterviewAnswer
	 */
	public function setQuestion(\Entity\Rental\InterviewQuestion $question)
	{
		$this->question = $question;

		return $this;
	}

	/**
	 * @return \Entity\Rental\InterviewAnswer
	 */
	public function unsetQuestion()
	{
		$this->question = NULL;

		return $this;
	}

	/**
	 * @return \Entity\Rental\InterviewQuestion|NULL
	 */
	public function getQuestion()
	{
		return $this->question;
	}

	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Rental\InterviewAnswer
	 */
	public function setAnswer(\Entity\Phrase\Phrase $answer)
	{
		$this->answer = $answer;

		return $this;
	}

	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getAnswer()
	{
		return $this->answer;
	}
}
