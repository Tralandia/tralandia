<?php

namespace Extras\Forms\Container;

use AdminModule\Forms\Form;
use Doctrine\ORM\EntityManager;
use Entity\Currency;
use Entity\Rental\Rental;
use Environment\Collator;
use Environment\Environment;
use Nette\Forms\Container;
use Nette\Localization\ITranslator;
use Nette\DateTime;

class RentalPriceListContainer extends BaseContainer
{
	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \Entity\Rental\PricelistRow
	 */
	protected $pricelistRows;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	protected $translator;

	protected $priceForOptions;
	protected $importantLanguages;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	public function __construct(Rental $rental, EntityManager $em, Environment $environment)
	{
		parent::__construct();
		$this->em = $em;

		$this->environment = $environment;
		$this->rental = $rental;
		$this->translator = $environment->getTranslator();
		$languageRepository = $em->getRepository(LANGUAGE_ENTITY);
		$centralLanguage = $languageRepository->find(CENTRAL_LANGUAGE);
		$this->importantLanguages = $this->rental->getPrimaryLocation()->getImportantLanguages($centralLanguage);


		$rows = $em->getRepository(RENTAL_PRICE_FOR_ENTITY)->findAll();
		foreach($rows as $row) {
			$firstPart = $this->translator->translate($row->firstPart);
			$secondPart = $row->secondPart ? ' / ' . $this->translator->translate($row->secondPart) : NULL;
			$this->priceForOptions[$row->id] = $firstPart . $secondPart;
		}

		$this->addDynamic('list', $this->containerBuilder, 1);
	}


	/**
	 * @return \Entity\Language
	 */
	public function getEnvironmentLanguage()
	{
		return $this->environment->getLanguage();
	}


	/**
	 * @return array|\Entity\Language[]
	 */
	public function getImportantLanguages()
	{
		return $this->importantLanguages;
	}


	public function containerBuilder(Container $container)
	{
		$today = (new DateTime)->modify('today');

		$dateFromControl = $container->addAdvancedDatePicker('seasonFrom')
			->getControlPrototype()
			->setPlaceholder($this->translator->translate('505'));

		$dateFromControl
			->addCondition(Form::FILLED)
			->addRule(Form::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);

		$dateToControl = $container->addAdvancedDatePicker('seasonTo')
			->getControlPrototype()
			->setPlaceholder($this->translator->translate('506'));

		$dateToControl
			->addCondition(Form::FILLED)
			->addRule(Form::RANGE, 'o100160', [$today, $today->modifyClone('+1 years')]);

		$container->addText('price', 'o100078')
			->setOption('append', $this->rental->getCurrency()->getIso())
			->addCondition(Form::FILLED)
			->addRule(Form::FLOAT)
			->addRule(Form::RANGE, $this->translator->translate('o100105'), [0, 999999999999999]);

		$container->addSelect('priceFor', '', $this->priceForOptions);

		$noteContainer = $container->addContainer('note');

		foreach($this->importantLanguages as $language) {
			$iso = $language->getIso();

			$noteContainer->addText($iso, '')
				->getControlPrototype()
				->setPlaceholder($this->translator->translate('974'));
//				->setOption('help', $this->translator->translate('o100071', null, null, null, $language))
//				->addRule(\FrontModule\Forms\BaseForm::MAX_LENGTH, $this->translator->translate('o100101'), 70);

		}

		$container->addHidden('entityId', '');
	}

	public function getFormattedValues($asArray = FALSE)
	{
		$values = $asArray
			? ['list'=>[]]
			: \Nette\ArrayHash::from(['list'=>[]]);

		$customPricelistRowRepository = $this->em->getRepository(RENTAL_CUSTOM_PRICELIST_ROW_ENTITY);
		$priceForRepository = $this->em->getRepository(RENTAL_PRICE_FOR_ENTITY);

		$oldIds = [];
		foreach ($this->getComponents() as $control) {
			$list = $control->getValues();
			$i = 0;
			foreach($list as $key => $row) {
				$hasNote = FALSE;
				foreach($row['note'] as $text) {
					if(strlen($text)) $hasNote = TRUE;
				}
				if (!$row['seasonFrom'] && !$row['seasonTo'] && !$row['price'] && !$hasNote) continue;

				$rowEntity = NULL;
				if ($row['entityId']) {
					$rowEntity = $customPricelistRowRepository->find($row['entityId']);
					$oldIds[$row['entityId']] = true;
				}
				if (!$rowEntity) {
					$rowEntity = $customPricelistRowRepository->createNew();
				}
				/** @var $rowEntity \Entity\Rental\CustomPricelistRow */

				$rowEntity->setSort($i);
				$rowEntity->setSeasonFrom($row['seasonFrom']);
				$rowEntity->setSeasonTo($row['seasonTo']);
				$rowEntity->setFloatPrice($row['price']);
				$rowEntity->setPriceFor($priceForRepository->find($row['priceFor']));
				$note = $rowEntity->getNote();
				foreach($this->importantLanguages as $language) {
					$note->setOrCreateTranslationText($language, $row['note'][$language->getIso()]);
				}

				$row['entity'] = $rowEntity;
				$values['list'][$key] = $row;
				$i++;
			}
		}

		foreach($this->rental->getCustomPricelistRows() as $row) {
			if(array_key_exists($row->getId(), $oldIds)) continue;

			$this->rental->removeCustomPricelistRow($row);
			$this->em->remove($row);
		}

		return $values;
	}

	public function setDefaultsValues()
	{
		$priceLists = [];
		foreach($this->rental->getCustomPricelistRows() as $row) {
			$note = [];
			foreach ($row->getNote()->getTranslations() as $translation) {
				$language = $translation->getLanguage();
				$note[$language->getIso()] = $translation->getTranslation();
			}

			$priceLists[] = [
				'seasonFrom' => $row->getSeasonFrom(),
				'seasonTo' => $row->getSeasonTo(),
				'price' => $row->getPrice()->getSourceAmount(),
				'priceFor' => $row->getPriceFor()->getId(),
				'entityId' => $row->getId(),
				'note' => $note,
			];
		}

		$this->setDefaults(['list' => $priceLists]);
	}


	public function getMainControl()
	{
		return NULL;
	}


}
