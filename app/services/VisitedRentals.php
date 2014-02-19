<?php

use Nette\Http\Request;
use Nette\Http\Response;

class VisitedRentals {

	const COOKIE_VAR_NAME = 'visitedRentals';

	const COOKIE_EXPIRATION = '+1 week';

	const MAX_COUNT = 50;

	/**
	 * @var Nette\Http\Request
	 */
	protected $httpRequest;

	/**
	 * @var Nette\Http\Response
	 */
	protected $httpResponse;

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


	/**
	 * @param \Entity\Rental\Rental $rental
	 *
	 * @return $this
	 */
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
		$seen = $this->httpRequest->getCookie(self::COOKIE_VAR_NAME);
		$seen = array_filter(explode(',', $seen));

		if ($limit && count($seen) > $limit) {
			$seen = array_chunk($seen, $limit, TRUE)[0];
		}

		return $seen;
	}

	public function setSeen(array $seen = array())
	{
		if(count($seen) > self::MAX_COUNT) {
			$seen = array_chunk($seen, self::MAX_COUNT, TRUE)[0];
		}
		$seen = implode(',', array_unique($seen));
		$this->httpResponse->setCookie(self::COOKIE_VAR_NAME, $seen, strtotime(self::COOKIE_EXPIRATION));

		return $this;
	}

}
