<?php
namespace Extras;

/**
 * Helpers class
 *
 * @author Dávid Ďurika
 */
class Helpers {

	public function loader($helper)
	{
		if (method_exists($this, $helper)) {
			return callback($this, $helper);
		}
	}
}