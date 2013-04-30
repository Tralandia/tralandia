<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;

class PhraseCheckingSupportedGrid extends AdminGridControl {

	const FILTER_LANGUAGE = 'language';

	/**
	 * @var \Extras\Translator
	 */
	public $translator;

	/**
	 * @var \Environment\Collator
	 */
	public $collator;

	/** 
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepositoryAccessor;

	/** 
	 * @var \DictionaryManager\FindOutdatedTranslations
	 */
	protected $findOutdatedTranslations;

	public function __construct($repository, $findOutdatedTranslations, $languageRepositoryAccessor, $translator, $collator) {
		$this->languageRepositoryAccessor = $languageRepositoryAccessor;
		$this->findOutdatedTranslations = $findOutdatedTranslations;
		$this->translator = $translator;
		$this->repository = $repository;
		$this->collator = $collator;
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
			$languages = $this->languageRepositoryAccessor->get()->getSupportedForSelect($this->translator, $this->collator);

			$form = new \Nette\Forms\Container;
			$form->addSelect(self::FILTER_LANGUAGE, NULL, $languages);

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
		$language = NULL;
		if (isset($filter[self::FILTER_LANGUAGE])) {
			$language = $this->languageRepositoryAccessor->get()->find($filter[self::FILTER_LANGUAGE]);
		}

		$limit = $paginator->itemsPerPage;
		$offset = ($paginator->page - 1) * $paginator->itemsPerPage;
		$data = $this->findOutdatedTranslations->getWaitingForTranslation($language, $limit, $offset);

		return $data;
	}

}

interface IPhraseCheckingSupportedGridFactory {

	/**
	 * @return IPhraseCheckingSupportedGridFactory
	 */
	public function create();
}
