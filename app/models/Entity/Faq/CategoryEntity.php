<?php

namespace Entity\Faq;

use Entity\Phrase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="faq_category")
 */
class Category extends \Entity\BaseEntity {

	/**
	 * @var Collection
	 * @ORM\OneToOne(targetEntity="Entity\Phrase\Phrase", cascade={"persist", "remove"})
	 */
	protected $name;

	/**
	 * @var integer
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sort;

	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Entity\Faq\Question", mappedBy="category", cascade={"persist", "remove"})
	 */
	protected $questions;


	//@entity-generator-code --- NEMAZAT !!!

	/* ----------------------------- Methods ----------------------------- */		
	public function __construct()
	{
		parent::__construct();

		$this->questions = new \Doctrine\Common\Collections\ArrayCollection;
	}
		
	/**
	 * @param \Entity\Phrase\Phrase
	 * @return \Entity\Faq\Category
	 */
	public function setName(\Entity\Phrase\Phrase $name)
	{
		$this->name = $name;

		return $this;
	}
		
	/**
	 * @return \Entity\Phrase\Phrase|NULL
	 */
	public function getName()
	{
		return $this->name;
	}
		
	/**
	 * @param integer
	 * @return \Entity\Faq\Category
	 */
	public function setSort($sort)
	{
		$this->sort = $sort;

		return $this;
	}
		
	/**
	 * @return \Entity\Faq\Category
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
		
	/**
	 * @param \Entity\Faq\Question
	 * @return \Entity\Faq\Category
	 */
	public function addQuestion(\Entity\Faq\Question $question)
	{
		if(!$this->questions->contains($question)) {
			$this->questions->add($question);
		}
		$question->setCategory($this);

		return $this;
	}
		
	/**
	 * @param \Entity\Faq\Question
	 * @return \Entity\Faq\Category
	 */
	public function removeQuestion(\Entity\Faq\Question $question)
	{
		$this->questions->removeElement($question);
		$question->unsetCategory();

		return $this;
	}
		
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection of \Entity\Faq\Question
	 */
	public function getQuestions()
	{
		return $this->questions;
	}
}