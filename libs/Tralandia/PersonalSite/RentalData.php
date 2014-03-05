<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 05/03/14 14:23
 */

namespace Tralandia\PersonalSite;


use Nette;

class RentalData
{

	public function __construct($slug, Nette\Database\Connection $db)
	{

	}


	public function getName()
	{
		return 'foooo';
	}

}


interface IRentalDataFactory
{
	public function create($slug);
}
