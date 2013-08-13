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
	 * @var \Repository\BaseRepository
	 */
	protected $bannedPhoneRepository;

	/**
	 * @var \Repository\BaseRepository
	 */
	protected $bannedEmailRepository;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->bannedPhoneRepository = $em->getRepository(BANNED_PHONE_ENTITY);
		$this->bannedEmailRepository = $em->getRepository(BANNED_EMAIL_ENTITY);
	}


	/**
	 * @param Rental $rental
	 *
	 * @return $this
	 */
	public function banRental(Rental $rental)
	{
		$this->banEmail($rental->getEmail());
		$this->banPhone($rental->getPhone());

		return $this;
	}


	public function unbanRental(Rental $rental)
	{
		$this->unbanEmail($rental->getEmail());
		$this->unbanPhone($rental->getPhone());

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
			$bannedEmail = $this->bannedEmailRepository->createNew();
			$bannedEmail->setEmail($email);

			$this->bannedEmailRepository->save($bannedEmail);
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
			$this->bannedEmailRepository->delete($bannedEmail);
		}
	}


	/**
	 * @param $email
	 *
	 * @return \Entity\Rental\BannedEmail|null
	 */
	public function findBannedEmail($email)
	{
		return $this->bannedEmailRepository->findOneBy(['email' => $email]);
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
			$bannedPhone = $this->bannedPhoneRepository->createNew();
			$bannedPhone->setPhone($phone);

			$this->bannedPhoneRepository->save($bannedPhone);
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
			$this->bannedPhoneRepository->delete($bannedPhone);
		}
	}


	/**
	 * @param Phone $phone
	 *
	 * @return \Entity\Rental\BannedPhone|null
	 */
	public function findBannedPhone(Phone $phone)
	{
		return $this->bannedPhoneRepository->findOneBy(['phone' => $phone->getId()]);
	}
}
