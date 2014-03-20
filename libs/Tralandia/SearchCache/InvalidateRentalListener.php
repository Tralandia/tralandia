<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/22/13 3:35 PM
 */

namespace Tralandia\SearchCache;


use Doctrine\ORM\EntityManager;
use Entity\Rental\Rental;
use Robot\IUpdateRentalSearchCacheRobotFactory;
use Nette;
use Nette\Caching\Cache;

class InvalidateRentalListener implements \Kdyby\Events\Subscriber
{
	const CLEAR_SEARCH = 'CLEAR_SEARCH';
	const CLEAR_HOMEPAGE = 'CLEAR_HOMEPAGE';
	const CLEAR_DICTIONARY = 'CLEAR_DICTIONARY';
	const CLEAR_TEMPLATE = 'CLEAR_TEMPLATE';
	const CLEAR_RENTALPICKER = 'CLEAR_RENTALPICKER';

	/**
	 * @autowire
	 * @var \Robot\IUpdateRentalSearchCacheRobotFactory
	 */
	protected $updateRentalSearchCacheRobotFactory;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $templateCache;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $translatorCache;

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $mapSearchCache;


	public function __construct(Cache $templateCache, Cache $translatorCache, Cache $mapSearchCache, IUpdateRentalSearchCacheRobotFactory $updateRentalSearchCacheRobotFactory)
	{
		$this->templateCache = $templateCache;
		$this->mapSearchCache = $mapSearchCache;
		$this->translatorCache = $translatorCache;
		$this->updateRentalSearchCacheRobotFactory = $updateRentalSearchCacheRobotFactory;
	}

	public function getSubscribedEvents()
	{
		return [
			'FormHandler\RegistrationHandler::onSuccess',
			'FormHandler\RentalEditHandler::onSubmit' => 'onSuccess',
		];
	}


	public function onSuccess(Rental $rental, array $options = NULL)
	{
		if(!$options || in_array(self::CLEAR_SEARCH, $options)) {
			$this->updateRentalSearchCacheRobotFactory->create($rental->getPrimaryLocation())->runForRental($rental);
		}

		if(!$options || in_array(self::CLEAR_TEMPLATE, $options)) {
			$this->templateCache->clean([
				Cache::TAGS => ['rental/' . $rental->getId()],
			]);
		}

		if(!$options || in_array(self::CLEAR_DICTIONARY, $options)) {
			$this->translatorCache->remove($rental->getName()->getId());
			$this->translatorCache->remove($rental->getTeaser()->getId());
			foreach($rental->getInterviewAnswers() as $answer) {
				$this->translatorCache->remove($answer->getAnswer()->getId());
			}
		}

		if(!$options || in_array(self::CLEAR_RENTALPICKER, $options)) {
			$this->mapSearchCache->remove($rental->getId());
		}

		if($options && in_array(self::CLEAR_HOMEPAGE, $options)) { // pozor tu je vinimka !!!
			$this->templateCache->clean([
				Cache::TAGS => ['primaryLocation/' . $rental->getPrimaryLocation()->getIso(), 'home'],
			]);
		}

	}


}
