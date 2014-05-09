<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property \Tralandia\Phrase\Phrase $description m:hasOne(description_id:)
 * @property \Tralandia\Phrase\Phrase $name m:hasOne(name_id)
 * @property string $slug
 * @property int|null $maxCapacity
 * @property string|null $calendar
 * @property string|null $contactName
 * @property string|null $email
 * @property string|null $personalSiteUrl
 * @property \DateTime|null $calendarUpdated
 *
 * @property \Tralandia\User\User $user m:hasOne
 * @property \Tralandia\Currency $currency m:hasOne(currency_id)
 * @property \Tralandia\Rental\Type $type m:hasOne
 * @property \Tralandia\Contact\Address $address m:hasOne
 * @property \Tralandia\Contact\Phone|null $phone m:hasOne
 *
 * @property \Tralandia\Rental\Amenity[] $amenities m:hasMany
 * @property \Tralandia\Language\Language[] $spokenLanguages m:hasMany
 *
 * @property \Tralandia\Rental\InterviewAnswer[] $interviewAnswers m:belongsToMany m:filter(sort#question_id)
 * @property \Tralandia\Rental\Image[] $images m:belongsToMany m:filter(sort)
 * @property \Tralandia\Rental\Video[] $videos m:belongsToMany m:filter(sort)
 * @property \Tralandia\Rental\PriceListRow[] $priceList m:belongsToMany
 * @property \Tralandia\Rental\CustomPriceListRow[] $customPriceList m:belongsToMany m:filter(sort)
 * @property \Tralandia\Rental\PriceListFile[] $priceListFiles m:belongsToMany(rental_id:rental_pricelist)
 * @property \Tralandia\Rental\Unit[] $units m:belongsToMany(rental_id:rental_unit)
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
	 * @return int
	 */
	public function getDescriptionId()
	{
		return $this->row->description_id;
	}


	/**
	 * @return bool
	 */
	public function hasDescription()
	{
		return (bool) $this->description->getTranslationsCount();
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
					'questionFe' => $answer->question->getQuestionFeId(),
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


	/**
	 * @return bool
	 */
	public function isAnyQuestionAnswered()
	{
		foreach($this->interviewAnswers as $answer) {
			if($answer->answer->getTranslationsCount()) return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getSomeCurrency()
	{
		return $this->row->currency_id ? $this->currency : $this->getPrimaryLocation()->defaultCurrency;
	}


	/********************* PHOTOS / IMAGES / VIDEO *********************/


	/**
	 * @return \Tralandia\Rental\Image
	 */
	public function getMainPhoto()
	{
		$images = $this->getPhotos(1);
		return reset($images);
	}


	/**
	 * @alias getMainPhoto
	 * @return Image
	 */
	public function getMainImage()
	{
		return $this->getMainPhoto();
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

	/**
	 * @return \Tralandia\Rental\Video
	 */
	public function getMainVideo()
	{
		$video = $this->getFewVideos(1);
		return reset($video);
	}


	/**
	 * @param null $limit
	 * @param int $offset
	 *
	 * @return \Tralandia\Rental\Video[]
	 */
	public function getFewVideos($limit = NULL, $offset = 0)
	{
		return array_slice($this->videos, $offset, $limit);
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
	 * @param null $excluded
	 *
	 * @return array
	 */
	public function getAmenitiesGroupByType($excluded = NULL)
	{
		$return = [];
		$sort = [];
		foreach ($this->amenities as $amenity) {
			$type = $amenity->type;
			if(is_array($excluded) && in_array($type->slug, $excluded)) continue;

			$sort[$type->id] = $type->sorting;
			$return[$type->id][$amenity->id] = $amenity;
		}
		asort($sort);

		foreach($sort as $key => $value) {
			$sort[$key] = $return[$key];
		}

		return $sort;
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
