<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;
use Entity\Language;
use Environment\Collator;
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Routers\BaseRoute;

/**
 * PhraseRepository class
 *
 * @author Dávid Ďurika
 */
class LanguageRepository extends \Repository\BaseRepository {

	/**
	 * @return \Entity\Language
	 */
	public function findCentral()
	{
		return $this->find(CENTRAL_LANGUAGE);
	}

	public function findSupported($order = NULL) {
		$entityName = $this->_entityName;
		return $this->findBySupported($entityName::SUPPORTED, $order);
	}

	public function findLive($order = NULL) {
		$entityName = $this->_entityName;
		return $this->findByLive($entityName::LIVE, $order);
	}

	public function findSupportedQb() {

		$qb = $this->_em->createQueryBuilder();

		$qb->select('e')->from($this->_entityName, 'e')
			->andWhere($qb->expr()->eq('e.supported', ':status'))->setParameter('status', Language::SUPPORTED);

		return $qb;
	}

	public function getSupportedSortedByName(ITranslator $translator, Collator $collator) {
		$supported = $this->findSupported();

		$return = array();
		foreach ($supported as $key => $language) {
			$return[$translator->translate($language->name)] = $language;
		}

		 $collator->ksort($return);

		return $return;

	}


	public function getAllSortedByName(ITranslator $translator, Collator $collator) {
		$all = $this->findAll();

		$return = array();
		foreach ($all as $key => $language) {
			$return[$translator->translate($language->name)] = $language;
		}

		 $collator->ksort($return);

		return $return;

	}


	public function getLiveSortedByName(ITranslator $translator, Collator $collator) {
		$supported = $this->findLive();

		$return = array();
		foreach ($supported as $key => $language) {
			$return[$translator->translate($language->name)] = $language;
		}

		// TODO: Zoradit pomocou \Collator
		// $collator->asort($return);

		return $return;

	}


	/**
	 * @param ITranslator $translator
	 * @param Collator $collator
	 *
	 * @return array
	 */
	public function getSupportedForSelect(ITranslator $translator, Collator $collator)
	{
		$return = [];
		$rows = $this->findSupported();
		foreach($rows as $row) {
			$return[$row->id] = $translator->translate($row->name);
		}
		$collator->asort($return);

		return $return;

	}


	/**
	 * @param Collator $collator
	 *
	 * @return array
	 */
	public function getForAdminSearch(Collator $collator)
	{
		$return = [];
		$rows = $this->findSupported();
		/** @var $row \Entity\Language */
		foreach($rows as $row) {
			$return[$row->getId()] = Strings::upper($row->getIso());
		}
		$collator->asort($return);

		return $return;

	}

	/**
	 * @param \Nette\Localization\ITranslator $translator
	 * @param \Environment\Collator $collator
	 * @param Presenter $presenter
	 *
	 * @return array
	 */
	public function getForSelectWithLinks(ITranslator $translator, Collator $collator, Presenter $presenter = NULL)
	{
		$rows = $this->findSupported();
		$return = [];
		$htmlOptions = [];
		$elTemplate = Html::el('option');
		foreach($rows as $row) {
			/** @var $row \Entity\Language */
			$key = $row->getId();
			$el = clone $elTemplate;

			$name = $translator->translate($row->getName());
			$localName = $row->getName()->hasTranslationText($row) ? $row->getName()->getTranslationText($row) : NULL;
			$text = (!$localName || $name == $localName) ? $name : $name . ' (' . Strings::lower($localName) . ')';
			$return[$key] = $text;

			if($presenter) {
				$link = $presenter->link('Registration:default', [BaseRoute::LANGUAGE => $row]);
				$htmlOptions[$key] = $el->value($key)->addAttributes(['data-redirect' => $link])->setText($text);
			}
		}


		if($presenter) {
			$collator->asort($return);
			foreach($return as $key => $value) {
				$return[$key] = $htmlOptions[$key];
			}
		} else {
			$collator->asort($return);
		}

		return $return;
	}

}
