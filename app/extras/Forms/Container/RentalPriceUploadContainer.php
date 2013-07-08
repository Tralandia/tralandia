<?php

namespace Extras\Forms\Container;

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
		$container->addUpload('file', 'o100192');
		$container->addHidden('entity', 0);
//		$container->addSubmit('remove', '#remove')
//			->setValidationScope(FALSE)
//			->setAttribute('class', 'ajax')
//			->onClick[] = callback($this, 'removeElementClicked');
	}

	public function removeElementClicked(SubmitButton $button)
	{
		$replicator = $button->parent->parent;
		$replicator->remove($button->parent, TRUE);
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

		$rowsCount = $count + 1;

		for($i=0; $i < $rowsCount; $i++) {
			if (array_key_exists($i, $priceLists)) {
				$defaults = $priceLists[$i];
				$container = $this['list'][$i]->setValues($defaults);
				$componentFile = $container->getComponents()['file'];
				$componentFile->caption = basename($defaults['file']);
				$componentFile->setDisabled();
			} else {
				$this['list'][$i]->setValues([]);
			}
		}
	}

	public function getMainControl()
	{
		return NULL;
	}

	public function validate() {
		d('test');
	}

}
