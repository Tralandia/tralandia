<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 * @property int $maxCapacity
 * @property string $calendar
 * @property string $contactName
 * @property string $email
 * @property string $personalSiteUrl
 * @property \DateTime|null $calendarUpdated
 *
 * @property \Tralandia\User\User $user m:hasOne
 * @property \Tralandia\Rental\Type $type m:hasOne
 * @property \Tralandia\Contact\Address $address m:hasOne
 * @property \Tralandia\Contact\Phone $phone m:hasOne
 *
 * @property \Tralandia\Rental\Amenity[] $amenities m:hasMany
 * @property \Tralandia\Language\Language[] $spokenLanguages m:hasMany
 *
 * @property \Tralandia\Rental\InterviewAnswer[] $interviewAnswers m:belongsToMany
 * @property \Tralandia\Rental\Image[] $images m:belongsToMany
 * @property \Tralandia\Rental\PriceListRow[] $priceList m:belongsToMany
 * @property \Tralandia\Rental\PriceListFile[] $priceListFiles m:belongsToMany(rental_id:rental_pricelist)
 */
class Rental extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @var array
	 */
	private $_interview;

	/**
	 * @var array
	 */
	private $_formattedCalendar;

	/**
	 * @return int
	 */
	public function getNameId()
	{
		return $this->row->name_id;
	}


	/**
	 * @return int
	 */
	public function getTeaserId()
	{
		return $this->row->teaser_id;
	}

	/**
	 * @return string|NULL
	 */
	public function getContactEmail()
	{
		return $this->email ? $this->email : $this->getOwner()->login;
	}


	/**
	 * @return \Tralandia\User\User
	 */
	public function getOwner()
	{
		return $this->user;
	}


	/**
	 * @return \Tralandia\Location\Location
	 */
	public function getPrimaryLocation()
	{
		return $this->address->primaryLocation;
	}


	/**
	 * @return array|\DateTime[]
	 */
	public function getCalendar()
	{
		if(!$this->calendarUpdated) {
			return [];
		}

		if(!is_array($this->_formattedCalendar)) {
			$days = array_filter(explode(',', $this->row->calendar));
			$todayZ = date('z');
			$calendarUpdatedZ = $this->calendarUpdated->format('z');
			$thisYear = $this->calendarUpdated->format('Y');
			$nextYear = $thisYear + 1;
			$daysTemp = [];
			foreach ($days as $key => $day) {
				if ($calendarUpdatedZ <= $day && $todayZ > $day) continue;
				$year = $calendarUpdatedZ <= $day ? $thisYear : $nextYear;
				$daysTemp[] = \Nette\DateTime::createFromFormat('z Y G-i-s', "$day $year 00-00-00");
			}

			$this->_formattedCalendar = array_filter($daysTemp);
		}

		return $this->_formattedCalendar;
	}


	public function isCalendarEmpty()
	{
		return !(bool) count($this->getCalendar());
	}



	/**
	 * @return array
	 */
	public function getInterview()
	{
		if(!$this->_interview) {
			$interview = [];
			foreach($this->interviewAnswers as $answer) {
				$interview[] = Nette\ArrayHash::from([
					'answer' => $answer->getAnswerId(),
					'question' => $answer->question->getQuestionId(),
				]);
			}
			$this->_interview = $interview;
		}

		return $this->_interview;
	}


	/**
	 * @return \Tralandia\Rental\InterviewAnswer
	 */
	public function getFirstAnswer()
	{
		$answers = $this->interviewAnswers;
		return reset($answers);
	}


	/**
	 * @return bool
	 */
	public function isFirstQuestionAnswered()
	{
		$answer = $this->getFirstAnswer();
		return (bool) $answer->answer->getTranslationsCount();
	}


	/********************* PHOTOS / IMAGES *********************/


	/**
	 * @return \Tralandia\Rental\Image
	 */
	public function getMainPhoto()
	{
		$images = $this->getPhotos(1);
		return reset($images);
	}


	/**
	 * @param null $limit
	 * @param int $offset
	 *
	 * @return \Tralandia\Rental\Image[]
	 */
	public function getPhotos($limit = NULL, $offset = 0)
	{
		return array_slice($this->images, $offset, $limit);
	}


	/**
	 * @return int
	 */
	public function getPhotosCount()
	{
		return count($this->images);
	}

	/********************* AMENITIES *********************/

	/**
	 * @return \Tralandia\Rental\Amenity[]
	 */
	public function getImportantAmenities()
	{
		return $this->getAmenitiesByImportant(TRUE);
	}


	/**
	 * @param bool $important
	 *
	 * @return \Tralandia\Rental\Amenity[]
	 */
	public function getAmenitiesByImportant($important = TRUE)
	{

		$return = array();
		foreach ($this->amenities as $amenity) {
			if ($amenity->important == $important) {
				$return[] = $amenity;
			}
		}

		return $return;
	}

	/**
	 * @param $typeId
	 *
	 * @return \Tralandia\Rental\Amenity[]
	 */
	public function getAmenitiesByType($typeId)
	{
		$return = [];
		foreach($this->amenities as $amenity) {
			if($amenity->getTypeId() == $typeId) {
				$return[] = $amenity;
			}
		}
		return $return;
	}


	/**
	 * @param $slug
	 *
	 * @return null|\Tralandia\Rental\Amenity
	 */
	public function getAmenityBySlug($slug)
	{
		foreach($this->amenities as $amenity) {
			if($amenity->slug == $slug) {
				return $amenity;
			}
		}

		return NULL;
	}


	/**
	 * @return \Tralandia\Rental\Amenity[]
	 */
	public function getBoard()
	{
		return $this->getAmenitiesByType(\Tralandia\Rental\AmenityType::$slugToId['board']);
	}


	/**
	 * @return bool
	 */
	public function hasBoard()
	{
		return (bool) count($this->getBoard());
	}


	/**
	 * @return bool
	 */
	public function hasWifi()
	{
		return (bool) $this->getAmenityBySlug('wireless-internet-wifi');
	}


	/**
	 * @return null|\Tralandia\Rental\Amenity
	 */
	public function getAllowedPet()
	{
		$amenities = $this->getAmenitiesByType(\Tralandia\Rental\AmenityType::$slugToId['animal']);
		return reset($amenities);
	}


	/**
	 * @return bool|null
	 */
	public function isPetAllowed()
	{
		$amenity = $this->getAllowedPet();
		if(!$amenity) return NULL;

		return $amenity->slug != 'no-pets';
	}




}