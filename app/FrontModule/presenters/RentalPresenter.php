<?php

namespace FrontModule;

use Entity\Rental\Image;
use Entity\Rental\Rental;
use Model\Rental\IRentalDecoratorFactory;
use FrontModule\Forms\Rental\IReservationFormFactory;
use Nette\ArrayHash;
use Nette\Utils\Html;
use Nette\Utils\Strings;


class RentalPresenter extends BasePresenter {

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
	 * @var \LastSeen
	 */
	protected $lastSeen;

	/**
	 * @autowire
	 * @var \FrontModule\Forms\Rental\IReservationFormFactory
	 */
	protected $reservationFormFactory;

	/**
	 * @autowire
	 * @var \BaseModule\Components\CalendarControl
	 */
	protected $calendarControl;

	public function actionDetail($rental)
	{
		if($this->device->isMobile()) {
			$this->mobileDetail($rental);
		} else {
			$this->desktopDetail($rental);
		}
	}

	public function desktopDetail($rental) {
		/** @var $rental \Entity\Rental\Rental */
		if (!$rental) {
			throw new \Nette\InvalidArgumentException('$id argument does not match with the expected value');
		}

		$rentalService = $this->rentalDecoratorFactory->create($rental);
		$interviewAnswers = [];
		foreach ($rental->getInterviewAnswers() as $key => $answer) {
			$answerText = $this->translate($answer->getAnswer());
			if($answerText && strlen(trim($answerText))) {
				$interviewAnswers[] = $answer;
			}
		}

		$locality = $rental->address->locality;
		$link = $this->link('//list', array('location' => $locality));
		$localitySeo = $this->seoFactory->create($link, $this->getLastCreatedRequest());

		$this->template->rental = $rental;
		$this->template->rentalService = $rentalService;
		$this->template->locality = $localitySeo;
		$this->template->interviewAnswers = $interviewAnswers;

		$this->template->teaser = $this->translate($rental->teaser);

		$firstAnswer = $rental->getFirstInterviewAnswer();
		if ($firstAnswer) {
			$this->template->firstAnswer = \Nette\Utils\Strings::truncate($this->translate($firstAnswer->answer), 200);
		} else {
			$this->template->firstAnswer = NULL;
		}

		$this->template->pet = $rental->getPetAmenity();
		$this->template->ownerAvailability = $rental->getOwnerAvailability();


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
		$this->lastSeen->visit($rental);

		$this->setLayout('detailLayout');
	}


	public function pinterestShare(Rental $rental, Image $image) {
		$shareLink = $this->link('//Rental:detail', [$rental]);
		$shareText = $this->translate($rental->getName());
		$shareImage = $this->rentalImagePipe->request($image);
		return $this->shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage);
	}


	protected function getLastSearchResults($rental) {
		$lastSearch = $this->lastSearch;

		if (!$lastSearch->exists()) {
			return FALSE;
		}

		$bar = array();
		$bar['all'] = $lastSearch->getRentals();
		$bar['totalCount'] = count($bar['all']);
		$bar['currentKey'] = array_search($rental->id, $bar['all']);

		$start = $bar['currentKey']>5 ? ($bar['currentKey']-5) : 0;
//		if (($left = count($bar['all']) - $start) < 12) {
//			$start = $start - (12-$left);
//		}

		$bar['all'] = array_slice($bar['all'], $start, 12);
		if (!isset($bar['currentKey'])) return FALSE;

		$barRentals = $this->context->rentalRepositoryAccessor->get()->findById($bar['all']);

		$barRentals = \Tools::sortArrayByArray($barRentals, $bar['all'], function($v) {return $v->getId();});


		$lastSearchResults = array();
		$lastSearchResults['rentals'] = $barRentals;
		$lastSearchResults['currentKey'] = $bar['currentKey']-($start > 0 ? $start : 0);
		$lastSearchResults['searchLink'] = $lastSearch->getUrl();
		$lastSearchResults['heading'] = $lastSearch->getHeading();
		$lastSearchResults['totalCount'] = $bar['totalCount'];

		if (isset($bar['all'][$lastSearchResults['currentKey']-1])) {
			$lastSearchResults['prevRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$lastSearchResults['currentKey']-1]);
		}

		if (isset($bar['all'][$lastSearchResults['currentKey']+1])) {
			$lastSearchResults['nextRental'] = $this->context->rentalRepositoryAccessor->get()->find($bar['all'][$lastSearchResults['currentKey']+1]);
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
		$comp = $this->calendarControl;

		return $comp;
	}
}
