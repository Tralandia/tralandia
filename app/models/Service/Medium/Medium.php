<?php

namespace Service\Medium;


class Medium extends \Service\BaseService {
	
	const MAIN_ENTITY_NAME = '\Entity\Medium\Medium';

	public static function createFromUrl($url) {
		$url = file_get_contents($url);
		return $url;
	}
	
}
