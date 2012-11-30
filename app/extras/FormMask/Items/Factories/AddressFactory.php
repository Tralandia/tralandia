<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class AddressFactory implements IFactory {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	protected $repositoryAccessor;

	/** @var Extras\Translator */
	protected $translator;

	/**
	 * @param Extras\Translator
	 * @param Extras\Models\Repository\RepositoryAccessor
	 */
	public function __construct(Extras\Translator $translator, Extras\Models\Repository\RepositoryAccessor $repositoryAccessor) {
		$this->repositoryAccessor = $repositoryAccessor;
		$this->translator = $translator;
	}
	
	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 * @param Extras\Books\Address
	 * @return Extras\FormMask\Items\Address
	 */
	public function create($name, $label, Extras\Models\Entity\IEntity $entity) {
		return new Extras\FormMask\Items\Address($name, $label, $entity, $this->translator, $this->repositoryAccessor);
	}
}