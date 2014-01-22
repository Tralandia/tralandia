<?php

namespace Extras\Books;

use libphonenumber\PhoneNumberFormat;
use Nette, Extras, Entity;
use Tralandia\BaseDao;

/**
 * Trieda sluzi ako databaza unikatnych telefonnych cisel.
 */
class Phone extends Nette\Object {

	/** @var \Tralandia\BaseDao */
	private $phoneDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationDao;

	/**
	 * @param \Tralandia\BaseDao $phoneDao
	 * @param \Tralandia\BaseDao $locationDao
	 */
	public function __construct(BaseDao $phoneDao, BaseDao $locationDao) {
		$this->phoneDao = $phoneDao;
		$this->locationDao = $locationDao;
	}

	/**
	 * Vyhlada cislo v DB
	 * - cislo musi byt v medzinarodnom formate
	 * @param string $number
	 * @return Entity\Contact\Phone|false
	 */
	public function find($number) {
		return $this->phoneDao->findOneByValue($this->prepareNumber($number));
	}


	/**
	 * @param $number
	 *
	 * @param null $prefix
	 *
	 * @return Entity\Contact\Phone|false
	 */
	public function getOrCreate($number, $prefix = NULL) {
		$number = $this->prepareNumber($number);
		if (!$phone = $this->find($number)) {
			$defaultCountry = NULL;
			if($prefix) $defaultCountry = $this->locationDao->findOneByPhonePrefix($prefix);
			$response = $this->serviceRequest($number, $defaultCountry);
			if (!$response || !$response->isValid) {
				return FALSE;
			}

			if (!$phone = $this->find($response->E164)) {
				$phone = $this->phoneDao->createNew();
				$primaryLocation = $this->locationDao->findOneByIso(strtolower($response->regionCode));

				$phone->setValue($this->prepareNumber($response->E164))
					->setInternational($response->international)
					->setNational($response->national)
					->setPrimaryLocation($primaryLocation);

				$this->phoneDao->save($phone);
			}
		}

		return $phone;
	}

	/**
	 * Je cislo validne?
	 * @param string $number
	 * @return bool
	 */
	public function isValid($number) {
		$response = $this->serviceRequest($number);
		return $response->validationResult->isValidNumber == 'true';
	}

	/**
	 * Vycisti a pripravi cislo na vyhladavanie
	 * @param string $number
	 * @return int
	 */
	private function prepareNumber($number) {
		return preg_replace('~[^0-9]~', '', $number);
	}


	/**
	 * @param $number
	 * @param Entity\Location\Location $defaultCountry
	 *
	 * @return \Nette\ArrayHash|null
	 */
	private function serviceRequest($number,Entity\Location\Location $defaultCountry = NULL) {
		$defaultCountry && $defaultCountryIso = strtoupper(substr($defaultCountry->getIso(), 0, 2));

		$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		try {
			$response = new Nette\ArrayHash();
			if($defaultCountry == 'AT') {
				$response->isValid = TRUE;
				$response->regionCode = $defaultCountry;
				$response->E164 = $number;
				$response->international = $number;
				$response->national = $number;
			} else {
				$phoneNumber = $phoneUtil->parse($number, isset($defaultCountryIso) ? $defaultCountryIso : 'EN');
				$response->isValid = $phoneUtil->isValidNumber($phoneNumber);
				$response->regionCode = $phoneUtil->getRegionCodeForNumber($phoneNumber);
				$response->E164 = $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);
				$response->international = $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
				$response->national = $phoneUtil->format($phoneNumber, PhoneNumberFormat::NATIONAL);
			}

			return $response;
		} catch(\libphonenumber\NumberParseException $e) {
			return NULL;
		}

	}
}
