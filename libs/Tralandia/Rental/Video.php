<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property string $service
 * @property string $videoId
 */
class Video extends \Tralandia\Lean\BaseEntity
{

	const SERVICE_YOUTUBE = 'youtube';
	const SERVICE_VIMEO = 'vimeo';


	public function getUrl()
	{
		return self::getUrlFor($this->service, $this->videoId);
	}


	public function getEmbedCode($width = 500, $height = 281)
	{
		return self::getEmbedCodeFor($this->service, $this->videoId, $width, $height);
	}


	public static function getUrlFor($service, $id)
	{
		if($service == self::SERVICE_YOUTUBE) {
			return 'https://www.youtube.com/watch?v=' . $id;
		} else if($service == self::SERVICE_VIMEO) {
			return 'http://vimeo.com/' . $id;
		} else {
			return null;
		}
	}


	public static function getEmbedCodeFor($service, $id, $width = 500, $height = 281)
	{
		if($service == self::SERVICE_YOUTUBE) {
			return '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$id.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		} else if($service == self::SERVICE_VIMEO) {
			return '<iframe src="//player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		} else {
			return null;
		}
	}

}
