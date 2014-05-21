<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 17/04/14 15:35
 */

namespace Migration;


use Nette;

class Migration extends \Phpmig\Migration\Migration
{
	use \ExecuteSqlFromFile;


	/**
	 * @return \SystemContainer|Nette\DI\Container
	 */
	protected function getDic()
	{
		return $this->getContainer()['dic'];
	}


	/**
	 * @return \Kdyby\Doctrine\EntityManager
	 */
	protected function getEm()
	{
		return $this->getContainer()['em'];
	}


	/**
	 * @return \LeanMapper\Connection
	 */
	protected function getLean()
	{
		return $this->getContainer()['lean'];
	}

}
