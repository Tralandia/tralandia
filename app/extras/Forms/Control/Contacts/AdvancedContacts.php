<?php

namespace Extras\Forms\Controls;

use Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Templating\FileTemplate,
	Nette\Forms\Controls\TextBase,
	Nette\Forms\Container,
	Nette\Latte\Engine;

/**
 * GmapFormControl
 * @author Jakub Jarabica (http://www.jam3son.sk)
 * @license MIT
 * 
 * @property-write string $template
 * 
 */
class AdvancedContacts extends TextBase {
		
	/** @var FileTemplate */
	private $template;

	/**
	 * @param string $label
	 * @param array $options
	 */
	public function __construct($label, $options = NULL) {
		parent::__construct($label);
		$this->control->setName('textarea');
		$this->value = '';

		if ($options !== NULL) {
			$this->options = array_merge($this->options, $options);
		}

		$this->template = dirname(__FILE__) . '/template.latte';
	}

	/** 
	 *
	 * @param string    $template path to template
	 * @param bool      is provided path full or relative to this script?
	 */
	public function setTemplate($template, $isPathFull = FALSE) {
		$this->template = ($isPathFull) ? $template : dirname(__FILE__).'/'.$template.'.latte';
	}

	/**
	 * Generates control's HTML
	 * 
	 * @return FileTemplate
	 */
	public function getControl() {
		$original = parent::getControl();
		$original->addClass('hide');
		$original->add($this->value);
		$id = $original->id;

		$template = new FileTemplate($this->template);
		$template->registerFilter(new Engine);

		// @todo toto treba dorobit $template->phonePrefixes = $this->getOption('phonePrefixes');
		$template->addressLocations = $this->getOption('addressLocations');
		$template->control = $original;

		return $template;
	}

	/**
	 * Generates label's HTML element.
	 * 	 
	 * @return Html
	 */
	public function getLabel($caption = NULL) {
		$label = parent::getLabel($caption);
		$label->for = NULL;
		return $label;
	}

	public static function register() {
		Container::extensionMethod('addAdvancedContacts', function (Container $_this, $name, $label, $options = NULL) {
			return $_this[$name] = new AdvancedContacts($label, $options);
		});
	}

}