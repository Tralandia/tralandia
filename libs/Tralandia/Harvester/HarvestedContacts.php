<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 21/11/13 15:26
 */

namespace Tralandia\Harvester;


use Entity\Contact\Phone;
use Entity\HarvestedContact;
use Entity\Rental\Rental;
use Nette;
use Tralandia\BaseDao;

class HarvestedContacts
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $harvestedContactsDao;


	/**
	 * @param BaseDao $harvestedContactsDao
	 */
	public function __construct(BaseDao $harvestedContactsDao)
	{
		$this->harvestedContactsDao = $harvestedContactsDao;
	}


	/**
	 * @param $email
	 *
	 * @return \Entity\Rental\Rental|null
	 */
	public function findRentalByEmail($email)
	{
		return $this->findRentalBy(HarvestedContact::TYPE_EMAIL, $email);
	}


	/**
	 * @param $phone
	 *
	 * @return \Entity\Rental\Rental|null
	 */
	public function findRentalByPhone($phone)
	{
		return $this->findRentalBy(HarvestedContact::TYPE_PHONE, $phone);
	}


	/**
	 * @param $type
	 * @param $value
	 *
	 * @return \Entity\Rental\Rental|null
	 */
	public function findRentalBy($type, $value)
	{
		$contact = $this->harvestedContactsDao->findOneBy(['value' => $value, 'type' => $type]);
		if($contact) {
			return $contact->rental;
		} else {
			return NULL;
		}
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param $type
	 * @param $value
	 *
	 * @return HarvestedContact
	 */
	public function add(\Entity\Rental\Rental $rental, $type, $value)
	{
		/** @var $entity \Entity\HarvestedContact */
		$entity = $this->harvestedContactsDao->createNew();
		$entity->rental = $rental;
		$entity->type = $type;
		$entity->value = $value;

		$this->harvestedContactsDao->save($entity);

		return $entity;
	}


	/**
	 * @param \Entity\Rental\Rental $rental
	 * @param $type
	 * @param $value
	 *
	 * @return HarvestedContact
	 */
	public function addIfNotExists(\Entity\Rental\Rental $rental, $type, $value)
	{
		if($value instanceof Phone) {
			$value = (string) $value;
		}
		$entity = $this->harvestedContactsDao->findOneBy(['type' => $type, 'value' => $value]);
		if($entity) {
			return $entity;
		} else {
			return $this->add($rental, $type, $value);
		}
	}
}
