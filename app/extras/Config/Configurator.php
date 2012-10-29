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
		'json' => 'Extras\Config\Form\Json'
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
				$this->form[$name] = new $this->formFields[$field->control->type]($name, $field->label);

				// parsovanie validacnych pravidiel
				if (isset($field->validation)) {
					foreach ((array)$field->validation as $params) {
						$method = current($params);
						unset($params[key($params)]);
						$this->form[$name]->addValidator($method, iterator_to_array($params));
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