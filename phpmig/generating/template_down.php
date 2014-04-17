<?php

use Phpmig\Migration\Migration;

class MigrationName extends Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		// $this->connection = $this->appContainer->notORMConnection;
		$this->executeSqlFromFile('down');
	}


}
