<?php

namespace Extras\Forms\Controls;


use Entity,
	Nette\ArrayHash,
	Nette\Utils\Html,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl;

/**
 * @author  Branislav Vaculčiak
 */
class AdvancedPhrase extends BaseControl {

	/** @var Entity\Phrase\Phrase */
	protected $phrase;

	/** @var array */
	protected $wrapper = array(
		'wrapperBox' => 'div class="input-wrapper phrase-control plain-text-simple"',
		'controlBox' => array(
			'box' => 'div class=input-prepend',
			'addOn' => 'span class=add-on',
			'inputClass' => 'span3 text input-arge'
		)
	);

	/**
	 * Setter frazy
	 * @param Entity\Phrase\Phrase
	 */
	public function setPhrase(Entity\Phrase\Phrase $phrase) {
		$this->phrase = $phrase;
	}

	/**
	 * Generovanie HTML kontrolu
	 * @return Html
	 */
	public function getControl() {
		$wrapper = ArrayHash::from($this->wrapper);
		$wrapperBox = Html::el($wrapper->wrapperBox);
		$parent = parent::getControl();
		$parent->type = 'text';

		if ($this->phrase instanceof Entity\Phrase\Phrase) {
			foreach ($this->phrase->getTranslations() as $translation) {
				$attributes = array();
				$box = Html::el($wrapper->controlBox->box);
				$box->add(Html::el($wrapper->controlBox->addOn)->setText($translation->language->iso));

				if ($this->phrase->sourceLanguage === $translation->language) {
					$attributes['data-special'] = 'sourceLanguage';
				}
				//TODO: sem pripravit dalsie vynimky
				if ($translation->language->iso == 'sk') {
					$attributes['data-special'] = 'štúrovina';
				}

				$control = clone $parent;
				$control->name = $control->name . '[' . $translation->language->iso . ']';
				$control->value = $this->hasPostedValue($translation->language->iso)
					? $this->value[$translation->language->iso]
					: $translation->translation;
				$control->class = $wrapper->controlBox->inputClass;
				if (empty($attributes)) {
					$box->addClass('hide');
				} else {
					$control->addAttributes($attributes);
				}

				$box->add($control);
				$wrapperBox->add($box);
			}
		}
		
		return $wrapperBox;
	}

	/**
	 * Zisti ci boli prijate post data a ci existuju pre ISO kod
	 * @return bool
	 */
	protected function hasPostedValue($iso) {
		return isset($this->value) && isset($this->value[$iso]);
	}

	/**
	 * Je kontrol vyplneny?
	 * @return bool
	 */
	public function isFilled() {
		return !empty($this->value);
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
		Container::extensionMethod('addAdvancedPhrase', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedPhrase($label);
		});
	}
}