<?php


class CalendarUpdateCache extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		/** @var $cm \Tralandia\Rental\CalendarManager */
		$cm = $this->getDic()->getByType('\Tralandia\Rental\CalendarManager');

		$qb = $this->getEm()->getRepository(RENTAL_ENTITY)->createQueryBuilder('r');
		$qb->where($qb->expr()->isNotNull('r.calendarUpdated'));
		$result = $qb->getQuery()->getResult();

		foreach($result as $rental) {
			$user = $rental->getUser();
			$cm->update($user);
		}

		$this->executeSqlFromFile('up');
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}
