<?php

namespace Extras\Books;

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

	/** @var string */
	private $serviceUrl = 'http://tra-devel.soft1.sk:8080/phonenumberparser?';


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
		if (!$phone = $this->find($number)) {
			$defaultCountry = NULL;
			if($prefix) $defaultCountry = $this->locationDao->findOneByPhonePrefix($prefix);
			$response = $this->serviceRequest($number, $defaultCountry);
			if (!isset($response->validationResult) || $response->validationResult->isValidNumber != 'true') {
				return FALSE;
			}
			$number = $this->prepareNumber($response->formattingResults->E164);

			if (!$phone = $this->find($number)) {
				$phone = $this->phoneDao->createNew();
				$primaryLocation = $this->locationDao->findOneByIso(strtolower($response->validationResult->phoneNumberForRegion));

				$phone->setValue($this->prepareNumber($response->formattingResults->E164))
					->setInternational($response->formattingResults->international)
					->setNational($response->formattingResults->national)
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
	 * Vyrvoti GET request na online sluzbu a vracia JSON response
	 * @param string $number
	 * @return int
	 */
	private function serviceRequest($number,Entity\Location\Location $defaultCountry = NULL) {
		$query = [];
		$query['phoneNumber'] = $number;
		if($defaultCountry) $query['defaultCountry'] = substr($defaultCountry->getIso(), 0, 2);
		$query = http_build_query($query);
		$response = file_get_contents($this->serviceUrl . $query);
		$response = Nette\Utils\Json::decode(str_replace('\'', '"', $response));
		return $response;
	}
}
