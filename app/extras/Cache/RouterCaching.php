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

	public function generateSegments() {
		$this->generateLocation();
		$this->generateRentalType();
		$this->generateTag();
		$this->generateAttractionType();
	}

	public function generatePage() {
		$locationsData = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['page']);
		$locationCache = array();
		while ($l = mysql_fetch_assoc($locationsData)) {
			$locationCache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('page_id2pathSegment', $locationCache);
	}

	public function generateAttractionType() {
		$locationsData = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['attractionType']);
		$locationCache = array();
		while ($l = mysql_fetch_assoc($locationsData)) {
			$locationCache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('attractionType_id2pathSegment', $locationCache);
	}

	public function generateLocation() {
		$locationsData = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['location']);
		$locationCache = array();
		while ($l = mysql_fetch_assoc($locationsData)) {
			$locationCache[$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('location_id2pathSegment', $locationCache);
	}

	public function generateRentalType() {
		$locationsData = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['rentalType']);
		$locationCache = array();
		while ($l = mysql_fetch_assoc($locationsData)) {
			$locationCache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('rentalType_id2pathSegment', $locationCache);
	}

	public function generateTag() {
		$locationsData = qNew('select * from routing_pathsegment where type = '.$this->pathSegmentTypes['tag']);
		$locationCache = array();
		while ($l = mysql_fetch_assoc($locationsData)) {
			$locationCache[$l['language_id']][$l['entityId']] = $l['pathSegment'];
		}
		$this->cache->save('tag_id2pathSegment', $locationCache);
	}


}