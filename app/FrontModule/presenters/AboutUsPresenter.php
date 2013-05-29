<?php

namespace FrontModule;

class AboutUsPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$userSiteReviewRepository = $this->em->getRepository(SITE_REVIEW_ENTITY);
		$this->template->testimonials = $userSiteReviewRepository->findBy([], ['created' => 'DESC']);
	}

}
