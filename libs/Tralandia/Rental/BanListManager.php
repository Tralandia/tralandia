<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 8/5/13 1:29 PM
 */

namespace Tralandia\Rental;


use Doctrine\ORM\EntityManager;
use Entity\Contact\Phone;
use Entity\Rental\Rental;
use Nette;

class BanListManager
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $bannedPhoneDao;

	/**
	 * @var \Tralandia\BaseDao
	 */
	protected $bannedEmailDao;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->bannedPhoneDao = $em->getRepository(BANNED_PHONE_ENTITY);
		$this->bannedEmailDao = $em->getRepository(BANNED_EMAIL_ENTITY);
	}


	/**
	 * @param Rental $rental
	 *
	 * @return $this
	 */
	public function banRental(Rental $rental)
	{
		$rental->getEmail() && $this->banEmail($rental->getEmail());
		$rental->getPhone() && $this->banPhone($rental->getPhone());

		return $this;
	}


	/**
	 * @param Rental $rental
	 *
	 * @return $this
	 */
	public function unbanRental(Rental $rental)
	{
		$this->unbanEmail($rental->getEmail());
		$rental->getPhone() && $this->unbanPhone($rental->getPhone());

		return $this;
	}


	/* ----------- Email manipulation ------------ */


	/**
	 * @param $email
	 *
	 * @return \Entity\Rental\BannedEmail
	 */
	public function banEmail($email)
	{
		$bannedEmail = $this->findBannedEmail($email);
		if (!$bannedEmail) {
			/** @var $bannedEmail \Entity\Rental\BannedEmail */
			$bannedEmail = $this->bannedEmailDao->createNew();
			$bannedEmail->setEmail($email);

			$this->bannedEmailDao->save($bannedEmail);
		}

		return $bannedEmail;
	}


	/**
	 * @param $email
	 */
	public function unbanEmail($email)
	{
		$bannedEmail = $this->findBannedEmail($email);

		if($bannedEmail) {
			$this->bannedEmailDao->delete($bannedEmail);
		}
	}


	/**
	 * @param $email
	 *
	 * @return \Entity\Rental\BannedEmail|null
	 */
	public function findBannedEmail($email)
	{
		return $this->bannedEmailDao->findOneBy(['email' => $email]);
	}


	/* ----------- Phone manipulation ------------ */


	/**
	 * @param Phone $phone
	 *
	 * @return \Entity\Rental\BannedPhone
	 */
	public function banPhone(Phone $phone)
	{
		$bannedPhone = $this->findBannedPhone($phone);
		if (!$bannedPhone) {
			/** @var $bannedPhone \Entity\Rental\BannedPhone */
			$bannedPhone = $this->bannedPhoneDao->createNew();
			$bannedPhone->setPhone($phone);

			$this->bannedPhoneDao->save($bannedPhone);
		}

		return $bannedPhone;
	}


	/**
	 * @param Phone $phone
	 */
	public function unbanPhone(Phone $phone)
	{
		$bannedPhone = $this->findBannedPhone($phone);

		if($bannedPhone) {
			$this->bannedPhoneDao->delete($bannedPhone);
		}
	}


	/**
	 * @param Phone $phone
	 *
	 * @return \Entity\Rental\BannedPhone|null
	 */
	public function findBannedPhone(Phone $phone)
	{
		return $this->bannedPhoneDao->findOneBy(['phone' => $phone->getId()]);
	}
}
