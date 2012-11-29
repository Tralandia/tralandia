<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class AddressFactory implements IFactory {

	/** @var Extras\Books\Address */
	protected $book;

	/**
	 * @param Extras\Books\Address
	 */
	public function __construct(Extras\Books\Address $book) {
		$this->book = $book;
	}
	
	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Books\Address
	 * @return Extras\FormMask\Items\Address
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Address($name, $label, $entity, $this->book);
	}
}