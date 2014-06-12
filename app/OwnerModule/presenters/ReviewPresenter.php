<?php

namespace OwnerModule;


use Nette\Application\UI\Multiplier;

class ReviewPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Tralandia\RentalReview\RentalReviewRepository
	 */
	protected $rentalReviewRepository;

	/**
	 * @autowire
	 * @var \Tralandia\SearchCache\InvalidateRentalListener
	 */
	protected $invalidateRentalListener;


	protected $rental;


	public function actionDefault($id)
	{
		$this->rental = $this->findRental($id);
		$this->checkPermission($this->rental, 'edit');


		$this->template->thisRental = $this->rental;
		$this->template->rentals = $this->loggedUser->getRentals();
		$this->template->avgRating = $this->rentalReviewRepository->getRentalAvgRate($this->rental);
	}

	public function renderDefault($id)
	{
		$this->template->reviews = $this->em->getRepository(RENTAL_REVIEW_ENTITY)->findBy(['rental' => $this->rental], ['created' => 'DESC']);
	}


	protected function createComponentAnswerForm()
	{
		$factory = function($reviewId) {
			$form = $this->simpleFormFactory->create();
			$form->getElementPrototype()->addClass('ajax');

			$form->addTextArea('answer');
			$form->addHidden('reviewId', $reviewId);
			$form->addSubmit('submit', 'odpovedat')
				->setAttribute('class', 'btn btn-default');

			$form->onSuccess[] = $this->processAnswerForm;

			return $form;
		};

		return new Multiplier($factory);
	}

	public function processAnswerForm($form)
	{
		$values = $form->getValues();

		$review = $this->em->getRepository(RENTAL_REVIEW_ENTITY)->find($values->reviewId);
		$this->checkPermission($review->rental, 'edit');

		$review->ownerAnswer = $values->answer;

		$this->em->flush($review);

		$this->invalidateRentalListener->onSuccess($review->rental);
		$this->invalidateControl('reviews');
	}

}
