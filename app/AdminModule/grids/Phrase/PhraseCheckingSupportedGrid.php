<?php

namespace AdminModule\Grids;

use AdminModule\Components\AdminGridControl;
use Tralandia\BaseDao;

class PhraseCheckingSupportedGrid extends AdminGridControl {

	const FILTER_LANGUAGE = 'language';

	/**
	 * @var \Tralandia\Localization\Translator
	 */
	public $translator;

	/**
	 * @var \Environment\Collator
	 */
	public $collator;

	/**
	 * @var \Dictionary\FindOutdatedTranslations
	 */
	protected $findOutdatedTranslations;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $languageDao;


	public function __construct($repository, $findOutdatedTranslations,BaseDao $languageDao, $translator, $collator) {
		$this->findOutdatedTranslations = $findOutdatedTranslations;
		$this->translator = $translator;
		$this->repository = $repository;
		$this->collator = $collator;
		$this->languageDao = $languageDao;
	}

	public function render() {

		$this->template->render();
	}

	public function createComponentGrid()
	{
		$grid = $this->getGrid();

		$grid->addColumn('id');
		$grid->addColumn('language');
		$grid->addColumn('translationId');

		$grid->setPagination(self::ITEMS_PER_PAGE, $this->getDataSourceCount);

		$grid->setFilterFormFactory(function() {
			$languages = $this->languageDao->getSupportedForSelect($this->translator, $this->collator);

			$form = new \Nette\Forms\Container;
			$form->addSelect(self::FILTER_LANGUAGE, NULL, $languages)
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
		$language = $this->getLanguageFromFilter($filter);

		$limit = $paginator->itemsPerPage;
		$offset = ($paginator->page - 1) * $paginator->itemsPerPage;
		$data = $this->findOutdatedTranslations->getWaitingForTranslation($language, $limit, $offset);

		return $data;
	}

	public function getDataSourceCount($filter, $order)
	{
		$language = $this->getLanguageFromFilter($filter);

		return $this->findOutdatedTranslations->getWaitingForTranslationCount($language);
	}


	protected function getLanguageFromFilter($filter)
	{
		$language = NULL;
		if (isset($filter[self::FILTER_LANGUAGE])) {
			$language = $this->languageDao->find($filter[self::FILTER_LANGUAGE]);
		}

		return $language;
	}

}

interface IPhraseCheckingSupportedGridFactory {

	/**
	 * @return IPhraseCheckingSupportedGridFactory
	 */
	public function create();
}
