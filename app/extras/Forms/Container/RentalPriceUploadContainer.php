<?php

namespace Extras\Forms\Container;

use Doctrine\ORM\EntityManager;
use Extras\Translator;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;

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

	protected $languages = [];


	public function __construct(\Entity\Rental\Rental $rental = NULL, \RentalPriceListManager $manager,
								\AllLanguages $allLanguages, Translator $translator, EntityManager $em)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->manager = $manager;
		$this->em = $em;
		$this->pricelists = $this->rental->getPricelists();

		$this->languages = $allLanguages->getForSelect(NULL, function($v) use($translator) {return $translator->translate($v->getName());});

		$this->addDynamic('list', $this->containerBuilder, 0);
		$this->setDefaultsValues();
	}

	public function containerBuilder(Container $container)
	{
		$container->addText('name', '');
		$container->addSelect('language', '', $this->languages);
		$container->addUpload('file', 'o100192');
		$container->addHidden('entity', 0);
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
				if (isset($row['file']) && $row['file']->isOk()) {
					/** @var $pricelist \Entity\Rental\PriceList */
					$pricelist = $this->manager->upload($row['file']);
					$pricelist->name = $row['name'];
					$pricelist->rental = $this->rental;
					$pricelist->language = $languageRepository->find($row['language']);
				} else if ($row['entity']) {
					/** @var $pricelist \Entity\Rental\PriceList */
					$pricelistRepository = $this->em->getRepository(RENTAL_PRICELIST_ENTITY);
					$pricelist = $pricelistRepository->find($row['entity']);
				} else {
					continue;
				}
				$row['entity'] = $pricelist;
				$values['list'][$key] = $row;
			}
		}
		return $values;
	}

	public function setDefaultsValues()
	{
		$priceLists = [];
		foreach($this->pricelists as $pricelist) {
			$priceLists[] = [
				'name' => $pricelist->name,
				'language' => $pricelist->language->getId(),
				'file' => $pricelist->filePath,
				'entity' => $pricelist->id
			];
		}

		$this->setDefaults(['list' => $priceLists]);
	}

	public function getMainControl()
	{
		return NULL;
	}

	public function validate() {
		d('test');
	}

}
