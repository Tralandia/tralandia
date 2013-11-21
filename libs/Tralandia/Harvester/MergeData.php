<?php
/**
 * This file is part of the Tralandia.
 * User: lukas
 * Created at: 11/7/13 1:29 PM
 */

namespace Tralandia\Harvester;

use Entity\HarvestedContact;
use Entity\Rental\Rental;
use Service\Rental\RentalCreator;
use Doctrine\ORM\EntityManager;


class MergeData {

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var HarvestedContacts
	 */
	private $harvestedContacts;


	/**
	 * @param HarvestedContacts $harvestedContacts
	 * @param \Kdyby\Doctrine\EntityManager $em
	 */
	public function __construct(HarvestedContacts $harvestedContacts, \Kdyby\Doctrine\EntityManager $em)
	{
		$this->em = $em;
		$this->harvestedContacts = $harvestedContacts;
	}

	public function merge($processingData, Rental $rental){
		$rentalDao = $this->em->getRepository(RENTAL_ENTITY);
		$classification = $rental->getClassification();
		$contactName = $rental->getContactName();
		$url = $rental->getUrl();
		$checkIn = $rental->getCheckIn();
		$checkOut = $rental->getCheckOut();
		$price = $rental->getPrice()->sourceAmount;
		$maxCapacity = $rental->getMaxCapacity();
		$bedroomCount = $rental->getBedroomCount();
		$phone = $rental->getPhone();
		$email = $rental->getEmail();
		$description = $rental->getFirstInterviewAnswer();
		if ($price == 0) {
			$rental->setFloatPrice($processingData['price']);
		}
		$data = [
			'classification' => empty($classification) ? $processingData['classification'] : $classification,
			'contactName' => empty($contactName) ? $processingData['contactName'] : $contactName,
			'url' => empty($url) ? $processingData['url'] : $url,
			'email' => empty($email) ? $processingData['email'] : $email,
			'checkIn' => empty($checkIn) ? $processingData['checkIn'] : $checkIn,
			'checkOut' => empty($checkOut) ? $processingData['checkOut'] : $checkOut,
			'maxCapacity' =>empty($maxCapacity) ? $processingData['maxCapacity'] : $maxCapacity,
			'bedroomCount' =>empty($bedroomCount) ? $processingData['bedroomCount'] : $bedroomCount,
			'phone' => empty($phone) ? reset($processingData['phone']) : $phone,
			'description' => empty($description) ? $processingData['description'] : $description,
		];

		$rental->setContactName($data['contactName'])
			->setBedroomCount($data['bedroomCount'])
			->setClassification($data['classification'])
			->setMaxCapacity($data['maxCapacity'])
			->setCheckIn($data['checkIn'])
			->setCheckOut($data['checkOut'])
			->setUrl($data['url'])
			->setEmail($data['email']);
		if (is_null($phone) && isset($data['phone'])) $rental->setPhone($data['phone']);


		$rentalDao->save($rental);

	}
}
