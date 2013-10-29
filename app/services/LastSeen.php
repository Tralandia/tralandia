<?php

use Nette\Http\Request;
use Nette\Http\Response;

class LastSeen {

	const COOKIE_VAR_NAME = 'visitedList';

	const COOKIE_EXPIRATION = '1+ week';

	/**
	 * @var Nette\Http\Request
	 */
	protected $httpRequest;

	/**
	 * @var Nette\Http\Response
	 */
	protected $httpResponse;

	/**
	 * @var string
	 */
	protected $seen;

	/**
	 * @var Tralandia\BaseDao
	 */
	private $rentalDao;


	public function __construct(\Tralandia\BaseDao $rentalDao, Request $httpRequest, Response $httpResponse)
	{
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		$this->rentalDao = $rentalDao;
	}

	public function visit(\Entity\Rental\Rental $rental)
	{
		$seen = $this->getSeen();
		array_push($seen, $rental->id);
		$this->setSeen($seen);

		return $this;
	}

	/**
	 * @param int $limit
	 *
	 * @return array|null
	 */
	public function getSeen($limit=NULL)
	{
		$this->seen = $this->httpRequest->getCookie(self::COOKIE_VAR_NAME);
		$seen = explode(',', $this->seen);

		if ($limit && count($seen)>$limit) {
			$start = count($seen) - $limit - 1;
			$seen = array_slice($seen, $start, $limit);
		}

		return $seen;
	}

	public function getSeenRentals($limit)
	{
		$rentals = array();
		foreach($this->getSeen($limit) as $rentalId) {
			$rental = $this->rentalDao->findOneById($rentalId);
			if (!$rental) continue;

			$rentals[] = $rental;
		}

		return $rentals;
	}

	public function setSeen($seen=array())
	{
		$this->seen = implode(',', array_unique($seen));
		$this->httpResponse->setCookie(self::COOKIE_VAR_NAME, $this->seen, strtotime(self::COOKIE_EXPIRATION));

		return $this;
	}

}
