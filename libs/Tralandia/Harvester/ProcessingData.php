<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Extras\Books\Phone;
use Kdyby\Doctrine\EntityManager;
use Nette\InvalidArgumentException;
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
		$this->requiredParameter($objectData['email'], $objectData['phone'], $objectData['images'], $objectData['name'], $objectData['gps']);
		$locationDao = $this->em->getRepository(LOCATION_ENTITY);
		$currencyDao = $this->em->getRepository(CURRENCY_ENTITY);
		$rentalTypeDao = $this->em->getRepository(RENTAL_TYPE_ENTITY);
		$languageDao = $this->em->getRepository(LANGUAGE_ENTITY);

		$objectData['primaryLocation'] = $objectData['language'];

		$formattedAddress = $this->getAddress(array(
			isset($objectData['gps'][0]) ? $objectData['gps'][0] : $this->getGps($objectData['address'])['latitude'],
			isset($objectData['gps'][1]) ? $objectData['gps'][1] : $this->getGps($objectData['address'])['longitude']));


		$prefix = $locationDao->findOneBy(['iso' => $objectData['language']])->phonePrefix;
		$objectData['phone'] = explode(',', $objectData['phone']);
		foreach($objectData['phone'] as $key => $value){
			$response = $this->phone->getOrCreate($value, $prefix);
			if ($response != false){
				$objectData['phone'][$key] = $response;
			} else {
				$objectData['phone'] = [];
			}
		}

		/* Osetrenie typu */
		$type = $rentalTypeDao->findOneBy(['slug' => $objectData['type']]);
		$objectData['type'] = is_null($type) ? 'hotel' : $objectData['type'];

		$spokenLanguage = $languageDao->findOneBy(['iso' => $objectData['language']]);
		$objectData['spokenLanguage'] = is_null($spokenLanguage) ? $languageDao->findOneBy(['iso' => $objectData['language']]): $spokenLanguage;

		$lastUpdate = new \DateTime($objectData['lastUpdate']);
        $address = $this->createAddress(array(
            'primaryLocation' => $locationDao->findOneBy(['iso' => $objectData['primaryLocation']]),
            'address' => isset($objectData['address']) ? $objectData['address'] : $this->getAddress($objectData['gps']),
            'latitude' => isset($objectData['gps'][0]) ? $objectData['gps'][0] : $this->getGps($objectData['address'])['latitude'],
			'longitude' => isset($objectData['gps'][1]) ? $objectData['gps'][1] : $this->getGps($objectData['address'])['longitude'],
        	'formattedAddress' => $formattedAddress
		));

		$data = [
			'email' => $objectData['email'],
			'phone' => $objectData['phone'],
//			'name' => $objectData['name'],
			'name' => $this->getName($languageDao->findOneBy(['iso' => $objectData['language']]), $objectData['name']),
			'primaryLocation' => $locationDao->findOneBy(['iso' => $objectData['primaryLocation']]),
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
			'price' => $this->getPrice($objectData['price'], $locationDao->findOneBy(['iso' => $objectData['primaryLocation']])),
//			'description' => $this->getDescription($languageDao->findOneBy(['iso' => $objectData['language']]), $objectData['description']),
			'description' => $objectData['description'],
			'images' => $objectData['images'],
			'bedroomCount' => $objectData['bedroomCount'],
			'lastUpdate' => $lastUpdate->format('Y-m-d H:i:s')
		];

        return($data);
    }

    protected function requiredParameter($email, $phone, $images, $name, $gps) {
        if ((isset($email) || isset($phone)) && count($images) && isset($name) && isset($gps)){
            return TRUE;
        } else {
			throw new InvalidArgumentsException('Chyba potrebny parameter');
        }
    }

    protected function createAddress($data) {
        $addressDao = $this->em->getRepository(ADDRESS_ENTITY);
        $address = $addressDao->createNew();
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $address->setGps(new \Extras\Types\LatLong($data['latitude'],$data['longitude']));
        }
		unset($data['latitude'], $data['longitude']);
        foreach ($data as $key => $value) {
            $address->$key = $value;
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

	protected function getInfo($address, $gps){
		if(isset($gps)){
			return $this->addressNormalizer->getInfoUsingGps(new \Extras\Types\Latlong($gps[0], $gps[1]));
		}else{
			return $this->addressNormalizer->getInfoUsingAddress($address);
		}
	}

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
