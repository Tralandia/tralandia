<?php

class MigrationName extends \Migration\Migration
{


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
