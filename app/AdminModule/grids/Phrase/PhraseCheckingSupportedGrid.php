<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class PhraseCheckingSupportedGrid extends AdminGridControl {

	public $translator;
	public $collator;

	protected $languageRepositoryAccessor;

	public function __construct($repository, $languageRepositoryAccessor, $translator, $collator) {
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->translator = $translator;
		$this->collator = $collator;
		$this->repository = $repository;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('language', 'Language');
		$grid->addColumn('entityAttribute', 'Entity attribute');
		$grid->addColumn('required');

		$grid->setFilterFormFactory(function() {
			$form = new \Nette\Forms\Container;
			$form->addSelect('language', NULL, $this->languageRepositoryAccessor->get()->getSupportedForSelect($this->translator, $this->collator))
				->setPrompt('---');

			return $form;
		});

		return $grid;
	}

	/**
	 * @param $filter
	 * @param $order
	 * @param \Nette\Utils\Paginator $paginator
	 *
	 * @return mixed
	 */
	public function getDataSource($filter, $order, \Nette\Utils\Paginator $paginator = NULL)
	{
		d($filter);
		$languages = $this->languageRepositoryAccessor->get()->findSupported();

		foreach ($languages as $key => $language) {
			$data = $this->repository->findMissingTranslationsBy($language);
			break;
		}

		return $data;
	}

}

interface IPhraseCheckingSupportedGridFactory {

	/**
	 * @return IPhraseCheckingSupportedGridFactory
	 */
	public function create();
}
