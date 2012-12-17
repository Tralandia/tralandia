<?php

namespace Entity\Faq;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="faq_question")
 */
class Question extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\ManyToOne(targetEntity="Entity\Faq\Category", inversedBy="questions")
	 */
	protected $category;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $question;

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $answer;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();
	}
		
	/**
	 * @param \Entity\Faq\Category
	 * @return \Entity\Faq\Question
	 */
	public function setCategory(\Entity\Faq\Category $category)
	{
		$this->category = $category;

		return $this;
	}
		
	/**
	 * @return \Entity\Faq\Question
	 */
	public function unsetCategory()
	{
		$this->category = NULL;

		return $this;
	}
		
	/**
	 * @return \Entity\Faq\Category|NULL
	 */
	public function getCategory()
	{
		return $this->category;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Faq\Question
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
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Faq\Question
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
		
	/**
	 * @param integer
	 * @return \Entity\Faq\Question
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return \Entity\Faq\Question
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