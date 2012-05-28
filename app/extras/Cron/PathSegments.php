<?php

namespace Extras\Cron;


class PathSegments {

	/**
	 * 
	 */
	public function updatePathsegments() {
		$em = \Extras\Models\Service::getEntityManager();
		$q = $em->createNativeQuery('
			SELECT id, iso
			FROM dictionary_language');
		$languages = $q->getArrayResult();
		debug($languages);
		return;
	}
}
