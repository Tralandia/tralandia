<?php


class InvoicingCreateServiceDuration extends \Migration\Migration
{


	public function up()
	{
		$this->executeSqlFromFile('up');

		$serviceDurationDao = $this->getEm()->getRepository(INVOICING_SERVICE_DURATION);

		$durations = [
			['No duration', \Entity\Invoicing\ServiceDuration::DURATION_NO_DURATION, 1],
			['1 Day', '+1 day', 1],
			['1 Week', '+1 week', 0],
			['2 Weeks', '+2 weeks', 1],
			['1 Month', '+1 month', 0],
			['2 Months', '+2 months', 0],
			['3 Months', '+3 months', 0],
			['4 Months', '+4 months', 0],
			['5 Months', '+5 months', 0],
			['6 Months', '+6 months', 0],
			['7 Months', '+7 months', 0],
			['8 Months', '+8 months', 0],
			['9 Months', '+9 months', 0],
			['10 Months', '+10 months', 0],
			['11 Months', '+11 months', 1],
			['1 Year', '+1 year', 0],
			['2 Years', '+2 years', 0],
			['3 Years', '+3 years', 0],
			['4 Years', '+4 years', 0],
			['5 Years', '+5 years', 1],
			['Forever', \Entity\Invoicing\ServiceDuration::DURATION_FOREVER, 0]
		];

		foreach($durations as $value) {
			/** @var $duration \Entity\Invoicing\ServiceDuration */
			$duration = $serviceDurationDao->createNew();
			$duration->setSlug($value[0]);
			$duration->setStrtotime($value[1]);
			$duration->setSeparatorAfter((bool) $value[2]);

			$this->getEm()->persist($duration);
		}
		$this->getEm()->flush();

	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
