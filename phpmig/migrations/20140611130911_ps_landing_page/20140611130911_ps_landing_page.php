<?php


class PsLandingPage extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		$this->executeSqlFromFile('up');

		$em = $this->getEm();
		/** @var $page \Entity\Page */
		$page = $em->getRepository(PAGE_ENTITY)->createNew();
		$page->destination = ':Front:PsLandingPage:default';
		$page->generatePathSegment = true;

		$en = $em->getRepository(LANGUAGE_ENTITY)->findOneBy(['iso' => 'en']);
		$sk = $em->getRepository(LANGUAGE_ENTITY)->findOneBy(['iso' => 'sk']);

		$titlePattern = $page->getTitlePattern();
		$titlePattern->setOrCreateTranslationText($en, 'Website');
		$titlePattern->setOrCreateTranslationText($sk, 'Website');

		$em->persist($page);
		$em->flush($page);

		/** @var $pathSegmentRobot \Robot\GeneratePathSegmentsRobot */
		$pathSegmentRobot = $this->getDic()->getByType('\Robot\GeneratePathSegmentsRobot');
		$pathSegmentRobot->runPages();
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}
