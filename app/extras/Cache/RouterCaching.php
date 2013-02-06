<?php

namespace Extras\Cache;


use Nette\Caching;


class RouterCaching extends \Nette\Object {

	protected $cache;
	protected $pathSegmentTypes;

	public function __construct(Caching\Cache $cache) {
		$this->cache = $cache;
		$this->pathSegmentTypes = \Extras\Route::getPathSegmentTypes();
	}

	public function generateDomain() {
		$data = qNew('select * from domain');
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['id']] = $l['domain'];
		}
		$this->cache->save('domain', $cache);
	}

	// segments
	public function generateSegments() {
		$this->generateLocation();
		$this->generateRentalType();
		//$this->generateAttractionType();
	}

	public function generatePage() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['page']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('page', $cache);
	}

	// public function generateAttractionType() {
	// 	$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['attractionType']);
	// 	$cache = array();
	// 	while ($l = mysql_fetch_assoc($data)) {
	// 		$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
	// 	}
	// 	$this->cache->save('attractionType', $cache);
	// }

	public function generateLocation() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['location']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('location', $cache);
	}

	public function generateRentalType() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['rentalType']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('rentalType', $cache);
	}


}