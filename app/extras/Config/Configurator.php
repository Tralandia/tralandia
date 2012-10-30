<?php

namespace Extras\Config;

use Nette;

class Configurator extends Nette\Object {

	const FORM_SETTINGS = 'form';
	const FORM_FIELDS = 'fields';

	private $formFields = array(
		'text' => 'Extras\Config\Form\Text',
		'select' => 'Extras\Config\Form\Select',
		'phrase' => 'Extras\Config\Form\Phrase',
		'yesno' => 'Extras\Config\Form\YesNo',
		'json' => 'Extras\Config\Form\Json',
		'neon' => 'Extras\Config\Form\Neon',
		'textarea' => 'Extras\Config\Form\Textarea',
		'price' => 'Extras\Config\Form\Price',
		'select' => 'Extras\Config\Form\Select',
		'checkbox' => 'Extras\Config\Form\Checkbox',
		'tinymce' => 'Extras\Config\Form\Tinymce'
	);

	/** @var Nette\ArrayHash */
	private $config = null;

	/** @var array */
	private $form = array();

	/**
	 * @param string
	 * @return Config
	 */
	public static function from($file) {
		return new self($file);
	}

	/**
	 * @param string
	 */
	public function __construct($file, $section = 'common') {
		$configurator = new Nette\Config\Configurator;
		$configurator->addParameters(array(
			'container' => array(
				'class' => 'ExtrasContainer'
			),
			'name' => 'nieco'
		));
		$configurator->setTempDirectory(TEMP_DIR)->addConfig($file, $section);
		$configurator->onCompile[] = function ($configurator, $compiler) {
			$compiler->addExtension('presenter', new PresenterExtension);
		};
		$configurator->onCompile[] = function ($configurator, $compiler) {
			$compiler->addExtension('form', new FormExtension);
		};
		$c = $configurator->createContainer();

		debug($c);

		foreach ($c->form as $s) {
			debug($s);
		}
		debug($c->form->parent);

		exit;


		$loader = new \Nette\Config\Loader;
		$this->config = Nette\ArrayHash::from($loader->load($file, $section));
		$this->buildForm();




	}


	/**
	 * Metoda vytvori z konfigu objektove formularove nastavenia jednotlivych poloziek
	 * @param string
	 */
	private function buildForm() {
		foreach ($this->config->{self::FORM_SETTINGS}->{self::FORM_FIELDS} as $name => $field) {
			if (isset($this->formFields[$field->control->type])) {
				$control = $this->form[$name] = new $this->formFields[$field->control->type]($name, $field->label);
				if(isset($field->control->items)) debug($field->control->items);

				$control->setOptions('control', iterator_to_array($field->control));

				// parsovanie validacnych pravidiel
				if (isset($field->validation)) {
					foreach ((array)$field->validation as $params) {
						$method = current($params);
						unset($params[key($params)]);
						$control->addValidator($method, iterator_to_array($params));
					}
				}
			}
		}
	}

	/**
	 * Metoda vrati nastavenia formularu
	 * @return array
	 */
	public function getForm() {
		return $this->form;
	}
}