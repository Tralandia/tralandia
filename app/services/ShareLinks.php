<?php

class ShareLinks {

	/**
	 * @var Environment\Environment
	 */
	protected $environment;

	/**
	 * @var string
	 */
	public $facebookPage;

	/**
	 * @var string
	 */
	public $twitterProfile;

	/**
	 * @var string
	 */
	public $googlePlusProfile;

	/**
	 * @param \Environment\Environment $environment
	 */
	public function __construct(\Environment\Environment $environment) {
		$this->environment = $environment;
	}


	/**
	 * @param $facebook
	 * @param $twitter
	 * @param $googlePlus
	 */
	public function setPages($facebook, $twitter, $googlePlus)
	{
		$this->facebookPage = $facebook;
		$this->twitterProfile = $twitter;
		$this->googlePlusProfile = $googlePlus;
	}


	public function getFacebookShareTag($link)
	{
		$el = \Nette\Utils\Html::el('div');
		$el->{"data-facebook-src"}($link);

		return $el;
	}


	public function getGooglePlusProfileShareTag()
	{
		return $this->getGooglePlusShareTag($this->googlePlusProfile);
	}


	public function getGooglePlusShareTag($link)
	{
		$el = \Nette\Utils\Html::el('g:plusone');
		$el->href($link);
		$el->size('medium');
		$el->lang($this->environment->getLocale()->getGooglePlusLangCode());

		return $el;
	}

}
