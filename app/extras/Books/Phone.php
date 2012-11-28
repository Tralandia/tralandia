<?php

namespace Extras\Books;

use Nette, Extras, Entity;

/**
 * Treda sluzi ako databaza unikatnych telefonnych cisel.
 */
class Phone extends Nette\Object {

	/** @var Extras\Models\Repository\RepositoryAccessor */
	private $phoneRepository;

	/** @var string */
	private $serviceUrl = 'http://tra-devel.soft1.sk:8080/phonenumberparser?';

	/**
	 * @param Extras\Models\Repository\RepositoryAccessor $phoneRepository
	 */
	public function __construct(Extras\Models\Repository\RepositoryAccessor $phoneRepository) {
		$this->phoneRepository = $phoneRepository;
	}

	/**
	 * Vyhlada cislo v DB
	 * - cislo musi byt v medzinarodnom formate
	 * @param string $number
	 * @return Entity\Contacts\Phone|false
	 */
	public function find($number) {
		return $this->phoneRepository->get()->findOneByPhone($this->prepareNumber($number));
	}

	/**
	 * Skusi najst cislo, ak nenajde vytvori nove a vrati jeho zaznam
	 * @param string $number
	 * @return Entity\Contacts\Phone
	 */
	public function getOrCreate($number) {
		if (!$phone = $this->find($number)) {
			$response = $this->serviceRequest($number);
			if ($response->validationResult->isValidNumber != 'true') {
				throw new \Exception('Telefonne cislo nie je validne');
			}

			$phone = $this->phoneRepository->get()->createNew();
			$phone->setPhone($this->prepareNumber($response->formattingResults->E164))
				->setInternational($response->formattingResults->international)
				->setNational($response->formattingResults->national)
				->setRegion($response->validationResult->phoneNumberForRegion);

			//TODO: este persiste a flush
			//$this->phoneRepository->get()->persist()
		}

		return $phone;
	}

	/**
	 * Vycisti a pripravi cislo na vyhladavanie
	 * @param string $number
	 * @return int
	 */
	private function prepareNumber($number) {
		return (int)str_replace(array(' ', '+'), array(null, null), $number);
	}

	/**
	 * Vyrvoti GET request na online sluzbu a vracia JSON response
	 * @param string $number
	 * @return int
	 */
	private function serviceRequest($number) {
		$query = http_build_query(array(
			'phoneNumber' => $this->prepareNumber($number)
		));
		$response = file_get_contents($this->serviceUrl . $query);
		$response = Nette\Utils\Json::decode(str_replace('\'', '"', $response));
		return $response;
	}
}