<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/3/13 12:41 PM
 */

namespace Tralandia\Doctrine;


use Nette;

class TablePrefixListener extends Nette\Object implements \Kdyby\Events\Subscriber
{

	/**
	 * Returns an array of events this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return ['\Doctrine\ORM\Events::loadClassMetadata'];
	}

	public function loadClassMetadata()
	{
		$tablePrefix = new \DoctrineExtensions\TablePrefix(NULL, '_');
		return $tablePrefix;
	}


}
