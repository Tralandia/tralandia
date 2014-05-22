<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Contact;

use Nette;


/**
 * @property string|null $formattedAddress
 * @property string $address
 * @property string $postalCode
 * @property float $latitude
 * @property float $longitude
 * @property \Tralandia\Location\Location $locality m:hasOne(locality_id:)
 * @property \Tralandia\Location\Location $primaryLocation m:hasOne(primaryLocation_id:)
 */
class Address extends \Tralandia\Lean\BaseEntity
{

	public function __toString()
	{
		if($this->formattedAddress) {
			return "{$this->formattedAddress}";
		} else {
			return "{$this->address}, {$this->postalCode}" . (isset($this->locality) ? ' ' . $this->locality->localName : NULL);
		}
	}


	/**
	 * @param \Extras\Types\Latlong
	 * @return \Entity\Contact\Address
	 */
	public function setGps(\Extras\Types\Latlong $latlong)
	{
		$this->latitude = $latlong->getLatitude();
		$this->longitude = $latlong->getLongitude();

		return $this;
	}

	/**
	 * @return \Extras\Types\Latlong
	 */
	public function getGps()
	{
		return new \Extras\Types\Latlong($this->latitude, $this->longitude);
	}



}
