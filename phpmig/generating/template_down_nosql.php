<?php

use Phpmig\Migration\Migration;

class MigrationName extends Migration
{

	/** @var \SystemContainer */
	private $appContainer;



	public function down()
	{
		$this->appContainer = $this->getContainer()['appContainer'];
		$this->connection = $this->appContainer->notORMConnection;
	}


}
