<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class EmailFactory implements IFactory {

	/** @var Extras\Books\Email */
	protected $book;

	/**
	 * @param Extras\Books\Email
	 */
	public function __construct(Extras\Books\Email $book) {
		$this->book = $book;
	}

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Books\Email
	 * @return Extras\FormMask\Items\Email
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Email($name, $label, $entity, $this->book);
	}
}
