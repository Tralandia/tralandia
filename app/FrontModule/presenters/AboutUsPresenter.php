<?php

namespace FrontModule;

class AboutUsPresenter extends BasePresenter
{

	public function renderDefault()
	{
		lt('start', 'lt-review');
		/** @var $siteReviewRepository \Tralandia\SiteReview\SiteReviewRepository */
		$siteReviewRepository = $this->getContext()->getByType('\Tralandia\SiteReview\SiteReviewRepository');
		$this->template->testimonials = $siteReviewRepository->findByEnvironment($this->environment);
		lt('start', 'lt-review');
	}

}
