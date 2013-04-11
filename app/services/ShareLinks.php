<?php

use Nette\Utils\Html;

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


	public function getFacebookPageShareTag()
	{
		return $this->getFacebookShareTag($this->facebookPage);
	}


	public function getFacebookShareTag($link)
	{
		$el = Html::el('a');
		$el->addAttributes([
			'class' => 'socialite facebook-like',
			'href' => 'http://www.facebook.com/sharer.php?u='.$link,
			'data-href' => $link,
			'data-send' => 'false',
			'data-layout' => 'box_count',
			'data-width' => '60',
			'data-show-faces' => 'false',
			'rel' => 'nofollow',
			'target' => '_blank',
		]);

		return $el;
	}


	public function getGooglePlusProfileShareTag()
	{
		return $this->getGooglePlusShareTag($this->googlePlusProfile);
	}


	public function getGooglePlusShareTag($link)
	{
		$el = Html::el('a');
		$el->addAttributes([
			'class' => 'socialite googleplus-one',
			'href' => 'https://plus.google.com/share?url='.$link,
			'data-size' => 'medium',
			'rel' => 'nofollow',
			'target' => '_blank',
		]);

		return $el;
	}


	public function getTwitterProfileShareTag()
	{
		return $this->getTwitterShareTag($this->twitterProfile);
	}


	public function getTwitterShareTag($link)
	{
		$el = Html::el('a');
		$el->addAttributes([
			'class' => 'socialite twitter-share',
			'href' => 'http://twitter.com/share',
			'data-text' => 'Socialite.js',
			'data-url' => $link,
			'data-count' => 'vertical',
			'rel' => 'nofollow',
			'target' => '_blank',
		]);

		return $el;
	}


	public function getPinterestShareTag($link)
	{
		$el = Html::el('a');
		$el->addAttributes([
			'class' => 'socialite pinterest-pinit',
			'href' => $link,
			'rel' => 'nofollow',
			'target' => '_blank',
		]);

		return $el;
	}

}
