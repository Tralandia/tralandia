<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;
use Nette\Forms\Container;

class RentalPriceUploadContainer extends BaseContainer
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \RentalPriceListManager
	 */
	protected $manager;

	protected $em;


	public function __construct(\Entity\Rental\Rental $rental = NULL, \RentalPriceListManager $manager, $em)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->manager = $manager;
		$this->em = $em;


		$this->addDynamic('list', $this->containerBuilder,2);

	}

	public function containerBuilder(Container $container)
	{
		$container->addText('name', '#name');
		$container->addSelect('language', '#language', [2,3,4,5]);
		$container->addUpload('file', '#file');
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray
			? ['list'=>[]]
			: \Nette\ArrayHash::from(['list'=>[]]);

		$pricelistRepository = $this->em->getRepository(RENTAL_PRICELIST_ENTITY);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);

		foreach ($this->getComponents() as $control) {
			$list = $control->getValues();
			foreach($list as $key => $row) {
				$rowEntity = NULL;
				if (isset($row->entityId)) {
					$rowEntity = $pricelistRepository->find($row->entityId);
				}
				if (!$rowEntity) {
					$rowEntity = $pricelistRepository->createNew();
				}
				$rowEntity->name = $this->name;
				$rowEntity->rental = $this->rental;
				$rowEntity->language = $languageRepository->find($row['language']);
				$rowEntity->filePath = '';

				$row['entity'] = $rowEntity;
				$values['list'][$key] = $row;
			}
		}
		return $values;
	}

	public function getMainControl()
	{
		return NULL;
	}


}
