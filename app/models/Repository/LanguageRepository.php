<?php
namespace Repository;

use Doctrine\ORM\Query\Expr;
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

	public function findSupported() {
		$entityName = $this->_entityName;
		return $this->findBySupported($entityName::SUPPORTED);
	}

	public function getSupportedSortedByName() {
		$supported = $this->findSupported();

		$sort = array();
		foreach ($supported as $key => $language) {
			$sort[$language->name->getTranslation($language)->getDefaultVariation()] = $key;
		}

		// TODO: Zoradit pomocou \Collator
		ksort($sort);

		$return = array();
		foreach ($sort as $name => $key) {
			$return[] = $supported[$key];
		}

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
