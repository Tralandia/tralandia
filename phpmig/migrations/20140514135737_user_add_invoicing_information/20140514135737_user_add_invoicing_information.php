<?php


class UserAddInvoicingInformation extends \Migration\Migration
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
