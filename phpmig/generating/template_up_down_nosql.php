<?php

use Phpmig\Migration\Migration;

class MigrationName extends Migration
{
	/** @var \SystemContainer */
	private $appContainer;


	public function up()
	{
		$this->appContainer = $this->getContainer()['appContainer'];
	}


	public function down()
	{
		$this->appContainer = $this->getContainer()['appContainer'];
	}


}
