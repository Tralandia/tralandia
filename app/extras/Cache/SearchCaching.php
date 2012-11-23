<?php

namespace Extras\Cache;


use Nette\Caching;

// $cache['location'][123] = 'liptov'
// $cache['rentalType'][144][12] = 'chaty'
// $cache['tag'][144][567] = 'lacne'


class SearchCaching extends \Nette\Object {

	protected $cache;
	protected $criteriaTypes = array(
		self::CRITERIA_COUNTRY,
		self::CRITERIA_LOCATION,
		self::CRITERIA_OBJECT_TYPE,
		self::CRITERIA_AREA_BOUNDRIES,
		self::CRITERIA_CAPACITY,
		self::CRITERIA_AMENITIES,
		self::CRITERIA_LANGUAGES_SPOKEN,
		self::CRITERIA_PRICE_CATEGORY
	);

	const CRITERIA_COUNTRY 			= 'country';
	const CRITERIA_LOCATION  		= 'location';
	const CRITERIA_OBJECT_TYPE 		= 'objecttype';
	const CRITERIA_AREA_BOUNDRIES 	= 'areaboundries';
	const CRITERIA_CAPACITY 		= 'capacity';
	const CRITERIA_AMENITIES 		= 'amenities';
	const CRITERIA_LANGUAGES_SPOKEN = 'languagesspoken';
	const CRITERIA_PRICE_CATEGORY 	= 'pricecategory';
	
	public function __construct(Caching\Cache $cache) {
		$this->cache = $cache;
	}

	public function setCriteria($criteria) {
		if (!is_array($criteria)) $criteria = array($criteria);
		
		foreach ($criteria as $entities) {
			if (!is_array($entities)) $entities = array($entities);

			foreach ($entities as $entity) {

				$criteriaType = str_replace(array("Entity\\", "\\"), "", get_class($entity));
				if (isset($this->criteriaTypes[$criteriaType])) {

				}
			}
		}
	}

}