<?php
namespace Extras;

/**
 * Helpers class
 *
 * @author Dávid Ďurika
 */
class Helpers {

	protected $mediumServiceFactory;

	public function __construct($mediumServiceFactory)
	{
		$this->mediumServiceFactory = $mediumServiceFactory;
	}

	public function loader($helper)
	{
		if (method_exists($this, $helper)) {
			return callback($this, $helper);
		}
	}


	public function mediumSrc($medium, $size)
	{
		$service = $this->mediumServiceFactory->create($medium);
		$src = $service->getThumbnail($size);
		return $src;
	}

}