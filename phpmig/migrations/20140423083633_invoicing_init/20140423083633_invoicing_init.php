<?php


class InvoicingInit extends \Migration\Migration
{


	public function up()
	{
		$this->executeSqlFromFile('up');

		/** @var $featured \Entity\Invoicing\ServiceType */
		$featured = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->createNew();
		$featured->setSlug('featured');
		$featured->getName()->getCentralTranslation()->setTranslation('Featured');

		$this->getEm()->persist($featured);

		/** @var $personalSite \Entity\Invoicing\ServiceType */
		$personalSite = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->createNew();
		$personalSite->setSlug('personalSite');
		$personalSite->getName()->getCentralTranslation()->setTranslation('Personal site');

		$this->getEm()->persist($personalSite);

		$this->getEm()->flush();
	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
