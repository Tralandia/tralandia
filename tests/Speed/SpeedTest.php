<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/6/13 12:46 PM
 */

namespace Tests\Speed;


use Nette;
use Tests\TestCase;

/**
 * @backupGlobals disabled
 */
class SpeedTest extends TestCase
{

	const RESULT_LIMIT = 100;


	public function testDoctrine()
	{
		$em = $this->getEm();

		$result = $em->getRepository(RENTAL_ENTITY)->findBy(['status' => 6], null, self::RESULT_LIMIT);

		lt('doctrine', 'lt-speed-test');
		/** @var $rental \Entity\Rental\Rental */
		foreach($result as $rental) {
			foreach($rental->getSpokenLanguages() as $language) {
				$iso = $language->getIso();
			}

			$slug = $rental->getSlug();
			$login = $rental->getUser()->getLogin();
		}
		lt('doctrine', 'lt-speed-test');
	}


	public function testLeanMapper()
	{
		/** @var $rentalRepository \Tralandia\Rental\RentalRepository */
		$rentalRepository = $this->getContext()->getByType('\Tralandia\Rental\RentalRepository');

		$result = $rentalRepository->findBy(['status' => 6], self::RESULT_LIMIT);

		lt('lean', 'lt-speed-test');
		/** @var $rental \Tralandia\Rental\Rental */
		foreach($result as $rental) {
			foreach($rental->spokenLanguages as $language) {
				$iso = $language->iso;
			}

			$slug = $rental->slug;
			$login = $rental->user->login;
		}
		lt('lean', 'lt-speed-test');

	}




}
