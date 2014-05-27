<?php


class PsForFree extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		$this->executeSqlFromFile('up_start');
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('up_end');
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}
