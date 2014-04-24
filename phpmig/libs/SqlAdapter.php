<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/04/14 13:04
 */

namespace Migration;


use Nette;
use Phpmig\Adapter\PDO\Sql;

class SqlAdapter extends Sql
{


	public function up(\Phpmig\Migration\Migration $migration)
	{
		$sql = $this->getQuery('up');

		$this->connection->prepare($sql)
			->execute(array(
				':version' => $migration->getVersion(),
				':name' => $migration->getName(),
			));

		return $this;
	}



	protected function getQuery($type)
	{
		if($this->pdoDriverName == 'mysql') {
			if($type == 'up') {
				return "INSERT into `{$this->tableName}` set version = :version, name = :name";
			}

			if($type == 'createSchema') {
				return "CREATE TABLE `{$this->tableName}` (`version` VARCHAR(255) NOT NULL, `name` VARCHAR(255) NOT NULL);";
			}
		}

		return parent::getQuery($type);
	}

}
