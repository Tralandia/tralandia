<?php

namespace OwnerModule;


class RatingPresenter extends BasePresenter {

	public function actionDefault($id = NULL)
	{
		$this->template->rentals = $this->loggedUser->getRentals();

		if ($id) {
			// vyfiltrovat hodnotenia pre konkretny objekt
			$this->template->thisRental = $this->rentalDao->find($id);
		}
	}

}
