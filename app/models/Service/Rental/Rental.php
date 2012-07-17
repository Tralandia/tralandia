<?php

namespace Service\Rental;


class Rental extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Rental\Rental';

	public function getLocationsByType($type, $limit = 0) {
		if (!is_array($type)) $type = array($type);

		$i=0;
		$return = array();
		foreach($this->locations as $location) {
			if (in_array($location->type->slug, $type)) {
				$return[] = $location;
				$i++;
			}
			if ($limit>0 && $limit==$i) break;
		}

		return \Nette\ArrayHash::from($return);

	}

	public function getMediaByType($type, $limit = 0) {
		if (!is_array($type)) $type = array($type);

		$i=0;
		$return = array();
		foreach($this->media as $media) {
			if ($media->type && in_array($media->type->id, $type)) {
				$return[] = $media;
				$i++;
			}
			if ($limit>0 && $limit==$i) break;
		}

		return \Nette\ArrayHash::from($return);

	}
	
}
