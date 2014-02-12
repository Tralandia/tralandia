<?php
namespace FrontModule\Components\SearchBar;

use Environment\Environment;
use Service\Rental\RentalSearchService;

class SearchHistoryControl extends \BaseModule\Components\BaseControl {

	/**
	 * @var \SearchHistory
	 */
	private $searchHistory;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	public function __construct(\SearchHistory $searchHistory, Environment $environment)
	{
		$this->searchHistory = $searchHistory;
		$this->environment = $environment;
	}


	public function render()
	{
		$template = $this->template;

		$history = $this->searchHistory->getHistory();

		foreach($history as $key => $row) {
			$formattedCriteria = [];
			if(isset($row[\SearchHistory::KEY_CRITERIA])) {
				$criteria = $row[\SearchHistory::KEY_CRITERIA];

				if(isset($criteria[RentalSearchService::CRITERIA_CAPACITY])) {
					$capacity = $criteria[RentalSearchService::CRITERIA_CAPACITY];
					$formattedCriteria[] = $capacity . ' ' . $this->presenter->translate('o490', $capacity);;
				}

				if(isset($criteria[RentalSearchService::CRITERIA_PRICE])) {
					$price = $criteria[RentalSearchService::CRITERIA_PRICE];
					$currency = $this->environment->getPrimaryLocation()->getDefaultCurrency()->getIso();
					if($price['from']) {
						$from = $this->getPresenter()->translate('o100093') . ' ' . $price['from'] . ' ';
					} else {
						$from = NULL;
					}

					if($price['to']) {
						$to = $this->getPresenter()->translate('o100094') . ' ' . $price['to'] . ' ';
					} else {
						$to = NULL;
					}

					$price = $from . $to . strtoupper($currency);
					$formattedCriteria[] = $price;
				}

				if(isset($criteria[RentalSearchService::CRITERIA_BOARD])) {
					$board = $this->presenter->findBoard($criteria[RentalSearchService::CRITERIA_BOARD]);
					if($board) {
						$formattedCriteria[] = $this->getPresenter()->translate($board->getName());
					}
				}

				if(isset($criteria[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE])) {
					$language = $this->presenter->findLanguage($criteria[RentalSearchService::CRITERIA_SPOKEN_LANGUAGE]);
					if($language) {
						$formattedCriteria[] = $this->getPresenter()->translate($language->getName());
					}
				}
			}



			$history[$key]['formattedCriteria'] = implode(', ', $formattedCriteria);
		}

		$template->history = $history;

		$template->render();
	}

}
