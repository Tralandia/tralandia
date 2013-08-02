<?php

namespace FrontModule;

use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;
use Nette\Utils\Html;
use Nette\Utils\Strings;

/**
 * @persistent(searchBar)
 */
class RentalListPresenter extends BasePresenter {

	/**
	 * @var array
	 */
	public $onSendFavoriteList = [];

	/**
	 * @autowire
	 * @var \Model\Rental\IRentalDecoratorFactory
	 */
	protected $rentalDecoratorFactory;

	/**
	 * @autowire
	 * @var \LastSearch
	 */
	protected $lastSearch;

	/**
	 * @autowire
	 * @var \User\FindOrCreateUser
	 */
	protected $findOrCreateUser;

	public function actionDefault($favoriteList, $email, $getDataForBreadcrumb)
	{
		if($this->device->isMobile()) {
			$this->setLayout("layoutMobile");
			$this->setView('mobileDefault');
		}

		if($favoriteList) {
			if(isset($email)) {
				$receiver = $this->findOrCreateUser->getUser($email, $this->environment);
				$this->onSendFavoriteList($favoriteList, $receiver);
				$this->sendJson(['success' => TRUE]);
			}
			$rentals = $favoriteList->getRentals();
			$itemCount = $rentals->count();
		} else if ($getDataForBreadcrumb) {
			$this->getDataForBreadcrumb();
		} else {
			/** @var $search \Service\Rental\RentalSearchService */
			$search = $this['searchBar']->getSearch();

			$itemCount = $search->getRentalsCount();

			$lastSearch = $this->lastSearch;
			$lastSearch->setRentals($search->getRentalsIds(NULL));
			$lastSearch->setUrl($this->pageSeo->getUrl());
			$lastSearch->setHeading($this->pageSeo->getH1());
		}

		$vp = $this['p'];
		/** @var $paginator \Nette\Utils\Paginator */
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = \Service\Rental\RentalSearchService::COUNT_PER_PAGE;
		$paginator->itemCount = $itemCount;


		/** @var $head \HeaderControl */
		$head = $this['head'];
		if(!$paginator->isFirst()) {
			if(($paginator->getPage() - 1) == 1) {
				$prevLink = $this->link('//this', ['p-p' => NULL]);
			} else {
				$prevLink = $this->link('//this', ['p-p' => $paginator->getPage() - 1]);
			}
			$prevTag = Html::el('meta')->rel('prev')->href($prevLink);
			$head->addTag($prevTag);
		}

		if(!$paginator->isLast()) {
			$nextLink = $this->link('//this', ['p-p' => $paginator->getPage() + 1]);
			$nextTag = Html::el('meta')->rel('next')->href($nextLink);
			$head->addTag($nextTag);
		}



		$this->template->totalResultsCount = $paginator->itemCount;

		if(isset($search)) {
			$rentals = $search->getRentalsIds($paginator->getPage());
		}


		//d($rentalsEntities);
//		$rentals = array();
//		foreach ($rentalsEntities as $rental) {
//			$rentals[$rental->id]['service'] = $this->rentalDecoratorFactory->create($rental);
//			$rentals[$rental->id]['entity'] = $rental;
//			$rentals[$rental->id]['featured'] = $orderCache->isFeatured($rental);
//		}

		$this->template->rentals = $rentals;
		$this->template->findRental = $this->findRentalData;
		$this->template->paginatorPage = $paginator->getPage();
	}


	public function getDataForBreadcrumb()
	{
		/** @var $search \Service\Rental\RentalSearchService */
		$search = $this['searchBar']->getSearch();
		$rentals = $search->getRentals();

		$json = [];

		foreach($rentals as $rental) {
			$row = [];
			$row['id'] = $rental->getId();
			$row['name'] = $this->translate($rental->getName());
			$row['url'] = $this->link('Rental:detail', ['rental' => $rental]);
			$json[] = $row;
		}

		$this->payload->listData = $json;
		$this->sendPayload();
	}


	public function actionRedirectToFavorites()
	{
		$link = $this->generateFavoriteLink();
		if($link) {
			$this->redirectUrl($link);
		} else {
			$this->redirect('Home:');
		}
	}

	public function findRentalData($id)
	{
		//d($id);
		if($id instanceof \Entity\Rental\Rental) {
			$rental = $id;
		} else {
			$rental = $this->rentalRepositoryAccessor->get()->find($id);
			if(!$rental) {
				throw new \Exception('ID: ' . $id);
			}
		}

		$firstInterviewAnswerText = NULL;
		if($rental->hasFirstInterviewAnswer()) {
			$answer = $rental->getFirstInterviewAnswer();
			$answerText = $this->translate($answer->getAnswer());
			if(strlen($answerText) > 2) {
				$firstInterviewAnswerText = $answerText;
			}
		}

		$nameIsTranslated = TRUE;
		$name = $rental->getName()->getTranslation($this->language);
		if(!$name || !$name->getTranslation()) {
			$nameIsTranslated = FALSE;
		}



		return [
			'service' => $this->rentalDecoratorFactory->create($rental),
			'firstInterviewAnswerText' => $firstInterviewAnswerText,
			'nameIsTranslated' => $nameIsTranslated,
		];
	}

	protected function createComponentP() {
		$vp = new \VisualPaginator();
		$vp->templateFile = APP_DIR.'/FrontModule/components/VisualPaginator/paginator.latte';

		return $vp;
	}
}
