<?php

namespace Extras\Types;

class Latlong extends BaseType {

	protected $dmsSeparators = array('°', '′', '″');
	protected $locationSeparator = ' ';
	protected $latitude;
	protected $longitude;

	public function __construct($latitude, $longitude) {
		$this->setGps($latitude, $longitude);
	}

	public function getLatitude() {
		if ($this->isValid()) {
			return $this->latitude;
		}
		return NULL;
	}

	public function getLongitude() {
		if ($this->isValid()) {
			return $this->longitude;
		}
		return NULL;
	}

	public function setGps($latitude, $longitude) {
		$this->latitude = $this->normalizeLocation($latitude);
		$this->longitude = $this->normalizeLocation($longitude);

		return $this->isValid();
	}

	// Retuns the value in DMS degrees
	// Format: 40°26′21″N 79°58′36″W.
	public function __toString() {
		if (!$this->isValid()) return '';

		return 
			$this->locationToString($this->latitude, 'latitude')
			.$this->locationSeparator
			.$this->locationToString($this->longitude, 'longitude')
		;
	}

	public function isValid() {
		if ($this->latitude != 0 && $this->longitude != 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//@todo - upravit, nech vrati NULL ak to nie je vobec OK, napr nula alebo vacsie ako povolene velkosti
	protected function normalizeLocation($value) {
		$value = str_replace(",",".", $value);
		
		if (is_numeric($value)) {
			return (float)$value;
		}
		if (is_numeric(str_replace(",",".", $value))) {
			return (float)str_replace(",",".", $value);
		}

	   	$value = strtolower(trim($value));

		$isNegative = FALSE;

	   	if (preg_match('/[w|s|-]/', $value)) {
	   		$isNegative = TRUE;
	   	}

	   	$value = preg_replace('/[n|e|w|s|-]/', '', $value);
	   	$value = preg_replace('/[^0-9|.]/', 'x', $value);

		$a = array_filter(explode('x', $value));
		$a = array_values($a);
		for ($i=0; $i < 3; $i++) { 
			if (!isset($a[$i])) $a[$i] = 0;
		}

		return (float) ($a[0]+($a[1]/60)+($a[2]/60/60))*(($isNegative)?(-1):(1));		
	}

	protected function locationToString($value, $type) {
		$values = array();
	    $vars = explode(".",$value);
	    $values[0] = (int)$vars[0];
	    $t = (float)("0.".$vars[1]);


	    $t = $t * 3600;
	    $values[1] = floor($t / 60);
	    $values[2] = (int) round($t - ($values[1] * 60));

	    if ($type == 'latitude') {
	    	$suffix = $values[0] < 0 ? 'S' : 'N';
	    } else {
	    	$suffix = $values[0] < 0 ? 'W' : 'E';
	    }

	    return 
	    	(string) abs($values[0]).$this->dmsSeparators[0]
	    	.$values[1].$this->dmsSeparators[1]
	    	.$values[2].$this->dmsSeparators[2]
	    	.$suffix;
	}
}