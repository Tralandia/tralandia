<?php


class PersonalsiteConfiguration extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('up');
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}
