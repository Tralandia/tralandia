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

	/**
	 * @var \Extras\Translator
	 */
	private $translator;


	public function __construct(\Entity\Rental\Rental $rental = NULL, \RentalPriceListManager $manager,
								\AllLanguages $allLanguages, Translator $translator, EntityManager $em)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->manager = $manager;
		$this->em = $em;
		$this->pricelists = $this->rental->getPricelists();

		$this->languages = $allLanguages->getForSelect(NULL, function($v) use($translator) {return $translator->translate($v->getName());});

		$this->addDynamic('list', $this->containerBuilder, $this->pricelists->count() + 1);
		$this->addHidden('oldIds');
		$this->translator = $translator;
	}

	public function containerBuilder(Container $container)
	{
		$container->addText('name', '')->setAttribute('placeholder', $this->translator->translate('o100191'));
		$container->addSelect('language', '', $this->languages)
			->setDefaultValue($this->rental->getPrimaryLocation()->getDefaultLanguage()->getId());
		$container->addUpload('file', 'o100192');
		$container->addHidden('filePath');
		$container->addHidden('entity', 0);
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray
			? ['list'=>[]]
			: \Nette\ArrayHash::from(['list'=>[]]);
		$languageRepository = $this->em->getRepository(LANGUAGE_ENTITY);
		/** @var $pricelist \Entity\Rental\PriceList */
		$pricelistRepository = $this->em->getRepository(RENTAL_PRICELIST_ENTITY);


		$oldIds = $this->getComponent('oldIds')->getValue();
		if(!$oldIds) return NULL;

		$oldIds = array_flip(explode(',', $oldIds));
		$list = $this->getComponent('list')->getValues();

		foreach($list as $key => $row) {
			if (isset($row['file']) && $row['file']->isOk()) {
				/** @var $pricelist \Entity\Rental\PriceList */
				$pricelist = $this->manager->upload($row['file']);
				$pricelist->name = $row['name'];
				$pricelist->language = $languageRepository->find($row['language']);
			} else if ($row['entity']) {
				$pricelist = $pricelistRepository->find($row['entity']);
			} else {
				continue;
			}
			$row['entity'] = $pricelist;
			$values['list'][$key] = $row;

			unset($oldIds[$pricelist->getId()]);
		}

		foreach($oldIds as $pricelistId => $value) {
			$pricelist = $pricelistRepository->find($pricelistId);
			$this->manager->delete($pricelist);
		}

		return $values;
	}

	public function setDefaultsValues()
	{
		$priceLists = [];
		$oldIds = [];
		foreach($this->pricelists as $pricelist) {
			$priceLists[] = [
				'name' => $pricelist->name,
				'language' => $pricelist->language->getId(),
				'filePath' => $pricelist->filePath,
				'entity' => $pricelist->id
			];
			$oldIds[] = $pricelist->getId();
		}

		$this->setDefaults(['list' => $priceLists, 'oldIds' => implode(',', $oldIds)]);
	}

	public function getMainControl()
	{
		return NULL;
	}

	public function validate() {
		//d('test');
	}

}
