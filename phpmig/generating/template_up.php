<?php


class MigrationName extends \Migration\Migration
{

	public function up()
	{
		$this->executeSqlFromFile('up');
	}

}
