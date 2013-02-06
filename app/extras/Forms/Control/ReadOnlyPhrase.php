<?php

namespace Extras\Forms\Controls;


use Entity,
	Nette\UnexpectedValueException,
	Nette\ArrayHash,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl;

/**
 * @author  Branislav Vaculčiak
 */
class ReadOnlyPhrase extends BaseControl {

	/** @const */
	const MAX_LEGTH = 255;

	/** @var Entity\Phrase\Phrase */
	protected $phrase;

	/** @var Entity\Language */
	protected $language;

	/** @var array */
	protected $linkCallback;

	/** @var array */
	protected $wrapper = array(
		'wrapperBox' => 'div class="btn-group phrase-control plain-text-complex"',
		'controlBox' => array(
			'button' => 'button data-toggle="dropdown" class="btn btn-success dropdown-toggle"',
			'list' => 'ul class=dropdown-menu',
			'item' => 'li'
		)
	);

	/**
	 * Generovanie HTML kontrolu
	 * @return Html
	 */
	public function getControl() {
		$wrapper = ArrayHash::from($this->wrapper);
		$wrapperBox = Html::el($wrapper->wrapperBox);
		$list = Html::el($wrapper->controlBox->list);
		$button = Html::el($wrapper->controlBox->button);
		$parent = parent::getControl();

		if ($this->phrase instanceof Entity\Phrase\Phrase) {
			foreach ($this->phrase->getTranslations() as $translation) {
				$item = Html::el($wrapper->controlBox->item);
				$item->setHtml(
					'<a href="' . $this->getLink($translation) .  '" lang="' . $translation->language->iso . '">'
					. $this->getFormatedItem($translation->language->iso, $translation->translation)
					. '</a>'
				);
				$list->add($item);
				if ($this->language && $this->language === $translation->language) {
					$defaultTranslation = $translation;
				}
			}
			if (!isset($defaultTranslation)) {
				$defaultTranslation = $this->phrase->getTranslations()->first();
			}
			$button->setHtml(
				$this->getFormatedItem($defaultTranslation->language->iso, $defaultTranslation->translation)
				. '<span class="caret pull-right"></span>'
			);
		}

		return $wrapperBox->add($button)->add($list);
	}

	/**
	 * Setter defaultnaho jazyka
	 * @param Entity\Language
	 * @return ReadOnlyPhrase
	 */
	public function setDefaultLanguage(Entity\Language $language = null) {
		$this->language = $language;
		return $this;
	}

	/**
	 * Setter frazy
	 * @param Entity\Phrase\Phrase
	 * @return ReadOnlyPhrase
	 */
	public function setPhrase(Entity\Phrase\Phrase $phrase) {
		$this->phrase = $phrase;
		return $this;
	}

	/**
	 * Settet callbacku na vytvorenie liniek
	 * @return ReadOnlyPhrase
	 */
	public function setLink($callback) {
		if (!is_callable($callback)) {
			throw new UnexpectedValueException("Zadany zly callback");
		}
		$this->linkCallback = $callback;
		return $this;
	}

	/**
	 * Vrati link pre item
	 * @return string
	 */
	public function getLink($translation) {
		if (is_callable($this->linkCallback)) {
			return call_user_func_array($this->linkCallback, array($translation));
		}
		return '#';
	}

	/**
	 * Vrati naformatovany item
	 * @return string
	 */
	public function getFormatedItem($key, $value) {
		$key = strtoupper($key);
		$value = Strings::truncate($value, self::MAX_LEGTH);
		return "<b>$key:</b> <span>$value</span>";
	}

	/**
	 * Je kontrol vyplneny?
	 * @return bool
	 */
	public function isFilled() {
		return false;
	}

	/**
	 * Vrati wrapper nastavenia
	 * @return array
	 */
	public static function &getWrapper() {
		return $this->wrapper;
	}

	/**
	 * Zaregistruje koponentu do formulara
	 */
	public static function register() {
		Container::extensionMethod('addReadOnlyPhrase', function (Container $_this, $name, $label) {
			return $_this[$name] = new ReadOnlyPhrase($label);
		});
	}
}