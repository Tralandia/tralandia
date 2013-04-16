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


	/**
	 * @return Html
	 */
	public function getFacebookPageShareTag()
	{
		return $this->getFacebookShareTag($this->facebookPage, '');
	}


	/**
	 * @param $link
	 * @param $text
	 *
	 * @return Html
	 */
	public function getFacebookShareTag($link, $text)
	{
		$el = Html::el('a')
			->class('socialite facebook-like')
			->href('http://www.facebook.com/sharer.php', ['u' => $link, 't' => $text])
			->data('href', $link)
			->data('layout', 'button_count')
			->data('send', 'false')
			->data('width', 60)
			->data('show-faces', 'false')
			->rel('nofollow')
			->target('_blank');

		return $el;
	}


	/**
	 * @return Html
	 */
	public function getGooglePlusProfileShareTag()
	{
		return $this->getGooglePlusShareTag($this->googlePlusProfile);
	}


	/**
	 * @param $link
	 *
	 * @return Html
	 */
	public function getGooglePlusShareTag($link)
	{
		$el = Html::el('a')
			->class('socialite googleplus-one')
			->href('https://plus.google.com/share', ['url' => $link])
			->data('href', $link)
			->data('size', 'medium')
			->rel('nofollow')
			->target('_blank');

		return $el;
	}


	/**
	 * @return Html
	 */
	public function getTwitterProfileShareTag()
	{
		return $this->getTwitterShareTag($this->twitterProfile, '');
	}


	/**
	 * @param $link
	 * @param $text
	 *
	 * @return Html
	 */
	public function getTwitterShareTag($link, $text)
	{
		$el = Html::el('a')
			->class('socialite twitter-share')
			->href('http://twitter.com/share')
			->data('url', $link)
			->data('text', $text)
			->data('count', 'none')
			->rel('nofollow')
			->target('_blank');

		return $el;
	}


	/**
	 * @param $link
	 * @param $text
	 * @param $media
	 *
	 * @return Html
	 */
	public function getPinterestShareTag($link, $text, $media)
	{
		$el = Html::el('a')
			->class('socialite pinterest-pinit')
			->href('http://pinterest.com/pin/create/button/', [
				'url' => $link,
				'media' => $media,
				'description' => $text,
			])
			->data('count-layout', 'horizontal')
			->rel('nofollow')
			->target('_blank');

		return $el;
	}

}
