<?php


class InvoicingNewServices extends \Migration\Migration
{


	public function up()
	{
		$this->executeSqlFromFile('up');
	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
