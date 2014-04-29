<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/04/14 12:20
 */

namespace Tests\Mocks;


use Nette;

class EntityManagerFactory
{

	public static function create(array $repositories = NULL)
	{
		$em = \Mockery::mock('\Kdyby\Doctrine\EntityManager');

		if(is_array($repositories)) {
			$em->byDefault()->shouldReceive('getRepository')->andReturnUsing(function($entity) use ($repositories){
				return $repositories[$entity];
			});
		}

		return $em;
	}

}
