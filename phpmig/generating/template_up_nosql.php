<?php


class MigrationName extends \Migration\Migration
{

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		$this->appContainer = $this->getContainer()['appContainer'];
	}


}
