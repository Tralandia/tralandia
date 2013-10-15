<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class PhoneFactory implements IFactory {

	/** @var Extras\Books\Phone */
	protected $book;

	/**
	 * @param Extras\Books\Phone
	 */
	public function __construct(Extras\Books\Phone $book) {
		$this->book = $book;
	}

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Books\Phone
	 * @return Extras\FormMask\Items\Phone
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Phone($name, $label, $entity, $this->book);
	}
}
