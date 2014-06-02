<?php


class InvoicingInit extends \Migration\Migration
{


	public function up()
	{
		$this->executeSqlFromFile('up');

		/** @var $type \Entity\Invoicing\ServiceType */
		$type = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->createNew();
		$type->setSlug('basic');
		$type->getName()->getCentralTranslation()->setTranslation('Basic');

		$this->getEm()->persist($type);

		/** @var $featured \Entity\Invoicing\ServiceType */
		$featured = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->createNew();
		$featured->setSlug('premium');
		$featured->getName()->getCentralTranslation()->setTranslation('Premium');

		$this->getEm()->persist($featured);

		/** @var $type \Entity\Invoicing\ServiceType */
		$type = $this->getEm()->getRepository(INVOICING_SERVICE_TYPE)->createNew();
		$type->setSlug('ps-premium');
		$type->getName()->getCentralTranslation()->setTranslation('Premium PS');

		$this->getEm()->persist($type);

		$this->getEm()->flush();
	}


	public function down()
	{
		$this->executeSqlFromFile('down');
	}


}
