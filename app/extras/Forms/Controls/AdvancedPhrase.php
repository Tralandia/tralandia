<?php

namespace Extras\Forms\Controls;


use Nette\Utils\Html,
	Tra\Utils\Arrays,
	Tra\Utils\Strings,
	Nette\Forms\Container,
	Nette\Forms\Controls\BaseControl,
	Extras\Types\Address;


class AdvancedPhrase extends BaseControl {

	public $phrase;

	public function setPhrase($phrase) {
		$this->phrase = $phrase;
	}


	// public function setValue($value) {
	// 	if(!is_array($value)) $value = $value->toArray();
	// 	$this->value = $value;
	// 	return $this;
	// }

	// public function getValue()
	// {
	// 	return is_array($this->value) ? $this->value : NULL;
	// }


	public function getControl() {
		$wrapper = Html::el('div')->class('btn-group phrase-control html-text');
		$control = parent::getControl();
		$colne = clone $control;
		$name = $control->name;
		$id = $control->id;

		$phrase = $this->phrase;
		if(!$phrase) {
			throw new \Exception("Chyba preklad: ".$name."! Asi nieje v DB kukni sa tam...");
		}
		if(!$this->getOption('inlineEditing')) {
			throw new \Exception("Nenastavil si InlineEditing pre frazu");
		}
		$inlineEditing = $this->getOption('inlineEditing');
		$inlineEditing->href->setParameter('id', $phrase->id);
		
		//$defaultLanguage = $this->getForm()->getDefaultLanguage();
		//$sourceLanguage = $phrase->getSourceLanguage();

		$button = Html::el('button')
					->class('btn btn-success dropdown-toggle')
					->addAttributes(array('data-toggle' => 'dropdown'));
		$wrapper->add($button);
		$ul = Html::el('ul')->class('dropdown-menu');
		$isFirst = TRUE;
		foreach ($phrase->translations as $translation) {
			$truncatedTranslation = Strings::truncate($translation->translation, 75);
			if($isFirst) {
				$button->add('<span class="caret pull-right"></span><div class="wrap"><b>'.strtoupper($translation->language->iso) . ': </b>'.$truncatedTranslation.'</div>');
				$isFirst = false;
			}

			$a = Html::el('a')
				->lang($translation->language->iso)
				->add('<b>'.strtoupper($translation->language->iso).': </b><span>'.$truncatedTranslation.'</span>')
				->href($inlineEditing->href->setParameter('languageIso', $translation->language->iso)->setParameter('display', 'modal'))
				->addAttributes(array('data-toggle'=>'ajax-modal'));
			$li = Html::el('li')->add($a);
			// @todo dorobit task #12649
			// if(!$this->getUser()->isAllowed()){
			// 	$li->addClass('disabled');
			// }
			$ul->add($li);
		}
		$wrapper->add($ul);
		
		return $wrapper;
	}

	/**
	 */
	public static function register()
	{
		Container::extensionMethod('addAdvancedPhrase', function (Container $_this, $name, $label) {
			return $_this[$name] = new AdvancedPhrase($label);
		});
	}

}