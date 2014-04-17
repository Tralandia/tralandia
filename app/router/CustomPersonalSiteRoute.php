<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 17/03/14 13:06
 */

namespace Routers;


use Nette;

class CustomPersonalSiteRoute extends PersonalSiteRoute
{


	protected function getRentalByDomain($domain, $params)
	{

		$rental = $this->rentalDao->findOneBy(['personalSiteUrl' => [$domain, 'www.' . $domain]]);
		return $rental;
	}

	protected function out($params, \Entity\Rental\Rental $rental)
	{
		$params['host'] = $rental->getPersonalSiteUrl();
		return $params;
	}


}


interface ICustomPersonalSiteRouteFactory {
	public function create($mask, $metadata);
}
