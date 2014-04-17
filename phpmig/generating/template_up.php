<?php

use Phpmig\Migration\Migration;

class MigrationName extends Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;


	public function up()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('up');
	}

}
