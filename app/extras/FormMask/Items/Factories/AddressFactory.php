<?php

namespace Extras\FormMask\Items\Foctories;

use Extras;

/**
 * @author Branislav Vaculčiak
 */
class AddressFactory implements IFactory {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	protected $repositoryAccessor;

	/** @var \Tralandia\Localization\Translator */
	protected $translator;

	/**
	 * @param Extras\Translator
	 * @param Extras\Models\Repository\RepositoryAccessor
	 */
	public function __construct(\Tralandia\Localization\Translator $translator, Extras\Models\Repository\RepositoryAccessor $repositoryAccessor) {
		$this->repositoryAccessor = $repositoryAccessor;
		$this->translator = $translator;
	}

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 * @param Extras\Books\Address
	 * @return Extras\FormMask\Items\Address
	 */
	public function create($name, $label, \Entity\BaseEntity $entity) {
		return new Extras\FormMask\Items\Address($name, $label, $entity, $this->translator, $this->repositoryAccessor);
	}
}
