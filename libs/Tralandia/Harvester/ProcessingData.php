<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Entity\Language;
use Entity\Location\Location;
use Extras\Books\Phone;
use Extras\Types\Latlong;
use Kdyby\Doctrine\EntityManager;
use Nette\InvalidArgumentException;
use Nette\Utils\Arrays;
use Nette\Utils\Validators;
use Service\Contact\AddressNormalizer;


class ProcessingData {


    /**
     * @var \Service\Contact\AddressNormalizer
     */
    private $addressNormalizer;
    /**
     * @var \Extras\Books\Phone
     */
    private $phone;
    /**
     * @var \Kdyby\Doctrine\EntityManager
     */
    private $em;


	public function __construct(AddressNormalizer $addressNormalizer, Phone $phone, EntityManager $em)
    {
        $this->addressNormalizer = $addressNormalizer;
        $this->phone = $phone;
        $this->em = $em;
	}


    public function process($objectData)
	{
		$latitude = Arrays::get($objectData, ['gps', 'latitude'], NULL);
		$longitude = Arrays::get($objectData, ['gps', 'longitude'], NULL);
		$latitudeTemp = abs($latitude);
		$longitudeTemp = abs($longitude);
		if(!$latitudeTemp || !$longitudeTemp) {
			$latitude = $longitude = NULL;
		}


		$this->requiredParameter($objectData['email'], $objectData['phone'], $objectData['images'], $objectData['name'], $latitude, $longitude, $objectData['address']);
		$locationDao = $this->em->getRepository(LOCATION_ENTITY);
		$currencyDao = $this->em->getRepository(CURRENCY_ENTITY);
		$rentalTypeDao = $this->em->getRepository(RENTAL_TYPE_ENTITY);
		$languageDao = $this->em->getRepository(LANGUAGE_ENTITY);

		/* Pomocou adresy hladam GPS ak nie su */
		$latLong = $this->getGps($objectData['address']);
		if (!$latitude || !$longitude){
			if (!$latitude = $latLong['latitude']){
				throw new InvalidArgumentsException('Chýba GPS (nenasla sa ani z adresy)');
			}
			if (!$longitude = $latLong['longitude']){
				throw new InvalidArgumentsException('Chýba GPS (nenasla sa ani z adresy)');
			}
		}

		/* Osetrenie typu */
		$type = $rentalTypeDao->findOneBy(['slug' => $objectData['type']]);
		$objectData['type'] = is_null($type) ? 'hotel' : $objectData['type'];

		$spokenLanguage = $language = $languageDao->findOneBy(['iso' => $objectData['language']]);
		$objectData['spokenLanguage'] = is_null($spokenLanguage) ? $languageDao->findOneBy(['iso' => $objectData['language']]): $spokenLanguage;

		$lastUpdate = new \DateTime($objectData['lastUpdate']);

        $address = $this->createAddress(new Latlong($latitude, $longitude), $language);

		$primaryLocation = $address->getPrimaryLocation();

		$prefix = $primaryLocation->phonePrefix;
		$objectData['phone'] = explode(',', $objectData['phone']);
		foreach($objectData['phone'] as $key => $value){
			$response = $this->phone->getOrCreate($value, $prefix);
			if ($response != false){
				$objectData['phone'][$key] = $response;
			} else {
				$objectData['phone'] = [];
			}
		}


		$data = [
			'email' => $objectData['email'],
			'phone' => $objectData['phone'],
//			'name' => $objectData['name'],
			'name' => $this->getName($languageDao->findOneBy(['iso' => $objectData['language']]), $objectData['name']),
			'primaryLocation' => $primaryLocation,
			'maxCapacity' => $objectData['maxCapacity'],
			'type' => $rentalTypeDao->findOneBy(['slug' => $objectData['type']]),
			'classification' => $objectData['classification'],
			'address' => $address,
			'contactName' => $objectData['contactName'],
			'url' => $objectData['url'],
			'editLanguage' => $this->getEditLanguage($objectData['language']),
			'spokenLanguage' => $spokenLanguage,
			'checkIn' => $objectData['checkIn'],
			'checkOut' => $objectData['checkOut'],
			'price' => $this->getPrice($objectData['price'], $primaryLocation),
//			'description' => $this->getDescription($languageDao->findOneBy(['iso' => $objectData['language']]), $objectData['description']),
			'description' => $objectData['description'],
			'images' => $objectData['images'],
			'bedroomCount' => $objectData['bedroomCount'],
			'lastUpdate' => $lastUpdate->format('Y-m-d H:i:s')
		];

        return($data);
    }

    protected function requiredParameter($email, $phone, $images, $name, $latitude, $longitude, $address)
	{
		if(!Validators::isEmail($email) && !$phone) {
			throw new InvalidArgumentsException('Chýba email aj tel. cislo');
		}

		if(!is_array($images) || !count($images)) {
			throw new InvalidArgumentsException('Chýbaju obrazky');
		}

		if(!$name) {
			throw new InvalidArgumentsException('Chýba nazov');
		}

		if((!$latitude || !$longitude) && !$address) {
			throw new InvalidArgumentsException('Chýba GPS alebo adresa');
		}
    }

    protected function createAddress(LatLong $latLong, Language $language) {
        $addressDao = $this->em->getRepository(ADDRESS_ENTITY);
		/** @var $address \Entity\Contact\Address */
		$address = $addressDao->createNew();
		$address->setGps($latLong);

		$response = $this->addressNormalizer->update($address, TRUE, $language);

		if($response == \GoogleGeocodeResponseV3::STATUS_OVER_QUERY_LIMIT) {
			throw new InvalidArgumentsException('GoogleGeocode: over query limit');
		}

		if ($response == FALSE){
			throw new InvalidArgumentsException('Chýba GPS');
		}

        return $address;
    }

	protected function getGps($address){
		if(!isset($address)){
			return FALSE;
		}
		$data = $this->addressNormalizer->getInfoUsingAddress($address);
		return $data;
	}

	protected function getAddress($gps){
		if(!isset($gps)){
			return FALSE;
		}
		$data = $this->addressNormalizer->getInfoUsingGps(new \Extras\Types\Latlong($gps[0], $gps[1]));
		$data = $data['route'] . ' ' . $data['street_number'] . ', ' . $data['locality']->localName . ', ' . $data['location']->localName;
		return $data;
	}

//	protected function getInfo($address, $gps){
//		if(isset($gps)){
//			return $this->addressNormalizer->getInfoUsingGps(new \Extras\Types\Latlong($gps[0], $gps[1]));
//		}else{
//			return $this->addressNormalizer->getInfoUsingAddress($address);
//		}
//	}

	protected function getPrice($price, $primaryLocation){
		$currencyDao = $this->em->getRepository(CURRENCY_ENTITY);
		$locationDao = $this->em->getRepository(LOCATION_ENTITY);
		$location = $locationDao->findOneBy(['iso' => $primaryLocation->iso]);
		$currency_id = $location->defaultCurrency->id;
//		$currency = $currencyDao->findOneBy(['iso' => $location->iso]);
		$exchangeRate = $currencyDao->findOneBy(['id' => $currency_id])->exchangeRate;
		$price = $price['amount'] * $exchangeRate;
		$currency = $currencyDao->findOneBy(['id' => $currency_id]);
		$price = new \Extras\Types\Price($price, $currency);
		$price = $price->convertToFloat($currency);
		return $price;
	}

//	protected function getDescription(\Entity\Language $language, $description){
//		$questionDao = $this->em->getRepository(INTERVIEW_QUESTION_ENTITY);
//		$answerDao = $this->em->getRepository(INTERVIEW_ANSWER_ENTITY);
//		$question = $questionDao->createNew();
//		$question = $questionDao->findOneBy(['id' => 1]);
////		$question = $question->setQuestion(new \Entity\Phrase\Phrase($description));
//		$answer = $answerDao->createNew();
//		$answer->setQuestion($question);
//		$answer->answer->createTranslation($language, $description);
////		$answer = $answer->setAnswer(new \Entity\Phrase\Phrase($description));
//		return $answer;
//	}
//
	protected function getEditLanguage($language){
		$languageDao = $this->em->getRepository(LANGUAGE_ENTITY);
		return $languageDao->findOneBy(['iso' => $language]);
	}

	protected function getName(\Entity\Language $language, $name){
		$phraseDao = $this->em->getRepository(PHRASE_ENTITY);
		$phraseTypeDao = $this->em->getRepository(PHRASE_TYPE_ENTITY);
		$phrase = $phraseDao->createNew();
		$phraseType = $phraseTypeDao->createNew();
		$type = $phrase->setType($phraseType);
		return $phrase->createTranslation($language, $name);
	}
}


class InvalidArgumentsException extends InvalidArgumentException {}
