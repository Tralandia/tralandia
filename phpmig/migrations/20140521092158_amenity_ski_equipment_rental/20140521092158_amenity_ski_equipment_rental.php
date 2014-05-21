<?php


class AmenitySkiEquipmentRental extends \Migration\Migration
{
	use ExecuteSqlFromFile;

	/** @var \SystemContainer */
	private $appContainer;



	public function up()
	{
		$em = $this->getEm();

		$type = $em->getRepository(AMENITY_TYPE_ENTITY)->find(20);
		$sk = $em->getRepository(LANGUAGE_ENTITY)->findOneBy(['iso' => 'sk']);
		$en = $em->getRepository(LANGUAGE_ENTITY)->findOneBy(['iso' => 'en']);

		/** @var $amenity \Entity\Rental\Amenity */
		$amenity = $em->getRepository(RENTAL_AMENITY_ENTITY)->createNew();
		$amenity->setType($type);
		$amenity->setSlug('ski-equipment-rental');

		$name = $amenity->getName();
		$name->setTranslationText($sk, 'Lyže a lyžiarske vybavenie');
		$name->setTranslationText($en, 'Ski & Equipment rental');


		$em->persist($amenity);
		$em->flush();

		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('up');
	}


	public function down()
	{
		// $this->appContainer = $this->getContainer()['appContainer'];
		$this->executeSqlFromFile('down');
	}


}
