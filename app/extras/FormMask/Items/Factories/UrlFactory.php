<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class UrlFactory implements IFactory {

	/** @var Extras\Books\Url */
	protected $book;

	/**
	 * @param Extras\Books\Url
	 */
	public function __construct(Extras\Books\Url $book) {
		$this->book = $book;
	}

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Books\Url
	 * @return Extras\FormMask\Items\Url
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Url($name, $label, $entity, $this->book);
	}
}
