<?php

namespace Extras\Cache;


use Nette\Caching;

// $cache['location'][123] = 'liptov'
// $cache['rentalType'][144][12] = 'chaty'
// $cache['tag'][144][567] = 'lacne'


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
		$this->generateTag();
		$this->generateAttractionType();
	}

	public function generatePage() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['page']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('page_id2pathSegment', $cache);
	}

	public function generateAttractionType() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['attractionType']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('attractionType_id2pathSegment', $cache);
	}

	public function generateLocation() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['location']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('location_id2pathSegment', $cache);
	}

	public function generateRentalType() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['rentalType']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('rentalType_id2pathSegment', $cache);
	}

	public function generateTag() {
		$data = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['tag']);
		$cache = array();
		while ($l = mysql_fetch_assoc($data)) {
			$cache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('tag_id2pathSegment', $cache);
	}


}