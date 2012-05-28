<?php

namespace Extras\Cron;


class PathSegments {

	/**
	 * 
	 */
	public function updatePathsegments() {

	}


	public function getSupportedLanguages() {
		$em = \Extras\Models\Service::getEntityManager();
		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm->addEntityResult('Entity\Dictionary\Language', 'l');
		$rsm->addFieldResult('l', 'id', 'id');
		$rsm->addFieldResult('l', 'iso', 'iso');
		$q = $em->createNativeQuery('
			SELECT id, iso
			FROM dictionary_language
			WHERE supported = 1
			', $rsm);

		$languages = array();
		foreach ($q->getArrayResult() as $key => $value) {
			$languages[$value['id']] = $value['iso'];
		}
		return $languages;
	}}
