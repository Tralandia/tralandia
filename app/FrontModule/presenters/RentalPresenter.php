<?php

namespace FrontModule;

use Entity\Rental\Image;
use Entity\Rental\Rental;
use FrontModule\Forms\Rental\IformatedCalendarFormFactory;
use Nette\ArrayHash;
use Nette\Utils\Html;
use Nette\Utils\Strings;


class RentalPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \SearchHistory
	 */
	protected $searchHistory;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;

	/**
	 * @autowire
	 * @var \BaseModule\Components\ICalendarControlFactory
	 */
	protected $calendarControlFactory;

	public function actionDetail($rental)
	{
		if($this->device->isMobile()) {
			$this->mobileDetail($rental);
		} else {
			$this->desktopDetail($rental);
		}
	}

	public function desktopDetail($rental) {
//		d('detail', t('detail'));
		/** @var $rental \Entity\Rental\Rental */
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}

		$interviewAnswers = [];
		foreach ($rental->getInterviewAnswers() as $key => $answer) {
			$answerText = $answer->getAnswer()->getTranslation($this->language);
			if($answerText && strlen(trim($answerText->getTranslation()))) {
				$interviewAnswers[] = $answer;
			}
		}

		$locality = $rental->address->locality;
		$link = $this->link('//list', array('location' => $locality));
		$localitySeo = $this->seoFactory->create($link, $this->getLastCreatedRequest());

		$this->template->rental = $rental;
		$this->template->locality = $localitySeo;
		$this->template->interviewAnswers = $interviewAnswers;

		$teaser = $rental->getTeaser()->getTranslation($this->language);
		$this->template->teaser = $teaser && $teaser->hasTranslation() ? $teaser->getTranslation() : NULL;

		$firstAnswer = $rental->getFirstInterviewAnswer();
		if ($firstAnswer && $firstAnswerTranslation = $firstAnswer->getAnswer()->getTranslation($this->language)) {
			$this->template->firstAnswer = \Nette\Utils\Strings::truncate($firstAnswerTranslation->getTranslation(), 200);
		} else {
			$this->template->firstAnswer = NULL;
		}

		$this->template->pet = $rental->getPetAmenity();
		$this->template->ownerAvailability = $rental->getOwnerAvailability();
		$this->template->isRentalFeatured = $this->isRentalFeatured;

		$formattedCalendar = [];
		foreach($rental->getCalendar() as $day) {
			if (!$day instanceof \DateTime) continue;
			$formattedCalendar[] = $day->format('d-m-Y');
		}

		$this->template->formatedCalendar = implode(',', $formattedCalendar);


		$this->template->dateUpdated = $rental->updated;

		$shareLinks = $this->shareLinks;

		$shareLink = $this->link('//Rental:detail', [$rental]);
		$shareText = $this->translate($rental->getName());
		$shareImage = $this->rentalImagePipe->request($rental->getMainImage());
		$this->template->twitterShareTag = $shareLinks->getTwitterShareTag($shareLink, $shareText);
		$this->template->googlePlusShareTag = $shareLinks->getGooglePlusShareTag($shareLink);
		$this->template->facebookShareTag = $shareLinks->getFacebookShareTag($shareLink, $shareText);
		$this->template->pinterestShareTag = $shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage);
		$this->template->pinterestShare = $this->pinterestShare;

		$lastSearchResults = $this->getLastSearchResults($rental);

		$shareLink = $lastSearchResults['searchLink'];
		$shareText = $lastSearchResults['heading'];
		$shareImage = $this->rentalImagePipe->request($rental->getMainImage());
		$navigationBarShareLinks = [
			'twitterTag' => $shareLinks->getTwitterShareTag($shareLink, $shareText),
			'googlePlusTag' => $shareLinks->getGooglePlusShareTag($shareLink),
			'facebookTag' => $shareLinks->getFacebookShareTag($shareLink, $shareText),
			'pinterestTag' => $shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage),
		];

		$this->template->lastSearchResults = $lastSearchResults;
		$this->template->navigationBarShareLinks = ArrayHash::from($navigationBarShareLinks);

		$this->visitedRentals->visit($rental);


		$this->setLayout('detailLayout');
//		d('detail', t('detail'));
	}


	public function pinterestShare(Rental $rental, Image $image) {
		$shareLink = $this->link('//Rental:detail', [$rental]);
		$shareText = $this->translate($rental->getName());
		$shareImage = $this->rentalImagePipe->request($image);
		return $this->shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage);
	}


	protected function getLastSearchResults($rental) {
		$searchHistory = $this->searchHistory;

		if (!$searchHistory->exists()) {
			return FALSE;
		}

		$lastSearch = $searchHistory->getLast();

		$bar = array();
		$bar['all'] = $lastSearch[\SearchHistory::KEY_RENTALS];
		$bar['totalCount'] = count($bar['all']);
		$bar['currentKey'] = array_search($rental->id, $bar['all']);

		$start = $bar['currentKey']>5 ? ($bar['currentKey']-5) : 0;
//		if (($left = count($bar['all']) - $start) < 12) {
//			$start = $start - (12-$left);
//		}

		$bar['all'] = array_slice($bar['all'], $start, 12);
		if (!isset($bar['currentKey'])) return FALSE;

		$barRentals = $this->rentalDao->findById($bar['all']);

		$barRentals = \Tools::sortArrayByArray($barRentals, $bar['all'], function($v) {return $v->getId();});


		$lastSearchResults = array();
		$lastSearchResults['rentals'] = $barRentals;
		$lastSearchResults['currentKey'] = $bar['currentKey']-($start > 0 ? $start : 0);
		$lastSearchResults['searchLink'] = $lastSearch[\SearchHistory::KEY_URL];
		$lastSearchResults['heading'] = $lastSearch[\SearchHistory::KEY_HEADING];
		$lastSearchResults['totalCount'] = $bar['totalCount'];

		if (isset($bar['all'][$lastSearchResults['currentKey']-1])) {
			$lastSearchResults['prevRental'] = $this->rentalDao->find($bar['all'][$lastSearchResults['currentKey']-1]);
		}

		if (isset($bar['all'][$lastSearchResults['currentKey']+1])) {
			$lastSearchResults['nextRental'] = $this->rentalDao->find($bar['all'][$lastSearchResults['currentKey']+1]);
		}

		if (!$lastSearchResults['totalCount']>1 && $this->template->navigationBarLastActive=='navigationBarSearchResults') {
			$this->template->navigationBarLastActive = 'navigationBarLastSeen';
		}

		return $lastSearchResults;
	}

	//
	// COMPONENTS
	//

	protected function createComponentReservationForm()
	{
		$form = $this->reservationFormFactory->create($this->getParameter('rental'));
		//$form->buildForm();

		$form->onSuccess[] = function ($form) {
			//$form->presenter->redirect('this');
			$form->presenter->template->fromSuccessMessage = 'o1029';
			$form->presenter->invalidateControl('reservationForm');
		};

		return $form;
	}

	protected function createComponentCalendar()
	{
		$comp = $this->calendarControlFactory->create($this->getParameter('rental'));

		return $comp;
	}
}
