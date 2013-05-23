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
	 * @var \Entity\Rental\Pricelist
	 */
	protected $pricelists;

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
		$this->pricelists = $this->rental->getPricelists();

		$this->addDynamic('list', $this->containerBuilder, 0);
		$this->setDefaultsValues();
	}

	public function containerBuilder(Container $container)
	{
		$container->addText('name', '#name');
		$container->addSelect('language', '#language', [2,3,4,5]);
		$container->addUpload('file', '#file');
		$container->addHidden('entity', 'entity', 0);
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray
			? ['list'=>[]]
			: \Nette\ArrayHash::from(['list'=>[]]);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);

		foreach ($this->getComponents() as $control) {
			$list = $control->getValues();
			foreach($list as $key => $row) {
				$file = $row['file'];
				if ($file && $file->isOk()) {
					/** @var $pricelist \Entity\Rental\PriceList */
					$pricelist = $this->manager->upload($file);
					$pricelist->name = $row['name'];
					$pricelist->rental = $this->rental;
					$pricelist->language = $languageRepository->find($row['language']);
				} else if ($row['entity']) {
					/** @var $pricelist \Entity\Rental\PriceList */
					$pricelistRepository = $this->em->getRepository(RENTAL_PRICELIST_ENTITY);
					$pricelist = $pricelistRepository->find($row['entity']);
				}
				$row['entity'] = $pricelist;
				$values['list'][$key] = $row;
			}
		}
		return $values;
	}

	public function setDefaultsValues()
	{
		$count=0;
		$priceLists = [];
		foreach($this->pricelists as $pricelist) {
			$priceLists[] = [
				'name' => $pricelist->name,
				'language' => $pricelist->language,
				'file' => $pricelist->filePath,
				'entity' => $pricelist->id
			];
			$count++;
		}

		$rowsCount = ($count==0) ? (2) : ($count+1);

		for($i=0; $i < $rowsCount; $i++) {
			$defaults = [];
			if (array_key_exists($i, $priceLists)) {
				$defaults = $priceLists[$i];
			}
			$this['list'][$i]->setValues($defaults);
		}
	}

	public function getMainControl()
	{
		return NULL;
	}


}
