<?php

namespace Extras\Config;

use Nette, Extras;

class PresenterExtension extends Nette\Config\CompilerExtension
{
	public $defaults = array(
		'configsDir' => '%appDir%/configs'
	);

	public $formFields = array(
		'text' => 'Extras\FormMask\Items\Foctories\TextFactory',
		'select' => 'Extras\FormMask\Items\Foctories\SelectFactory',
		'phrase' => 'Extras\FormMask\Items\Foctories\PhraseFactory',
		'yesno' => 'Extras\FormMask\Items\Foctories\YesNoFactory',
		'json' => 'Extras\FormMask\Items\Foctories\JsonFactory',
		'neon' => 'Extras\FormMask\Items\Foctories\NeonFactory',
		'textarea' => 'Extras\FormMask\Items\Foctories\TextareaFactory',
		'price' => 'Extras\FormMask\Items\Foctories\PriceFactory',
		'select' => 'Extras\FormMask\Items\Foctories\SelectFactory',
		'checkbox' => 'Extras\FormMask\Items\Foctories\CheckboxFactory',
		'tinymce' => 'Extras\FormMask\Items\Foctories\TinymceFactory'
	);

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		foreach (Nette\Utils\Finder::findFiles('*.neon')->in($config['configsDir']) as $path => $file) {
			$prefix = substr($file->getBasename(), 0, -5);
			$params = Nette\Utils\Neon::decode(file_get_contents($path));
			
			debug($params['parameters']);
			//if (isset($params['form'])) debug($params['form']['fields']);

			$builder->addDefinition($this->prefix($prefix))
				->setClass('Nette\DI\NestedAccessor', array('@container', $this->prefix($prefix)));

#			$builder->addDefinition($this->prefix($prefix . '.parameters'))
#				->setClass('ArrayIterator', $params['parameters'])
#				//->addSetup('from', $params['parameters'])
#				->setShared(FALSE)->setAutowired(FALSE);


			$generator = $builder->addDefinition($this->prefix($prefix . '.formGenerator'))
				->setClass('Extras\FormMask\Generator', array(new Nette\DI\Statement('Extras\FormMask\Mask')))
				->setAutowired(FALSE);

			$form = $builder->addDefinition($this->prefix($prefix . '.form'))
				->setClass('Extras\FormMask\FormFactory', array($generator));

			if (isset($params['form']) && isset($params['form']['fields'])) {
				foreach ($params['form']['fields'] as $name => $item) {
					$factory = '@item' . ucfirst($item['control']['type']) . 'Factory';

					if (isset($item['control']['type'])) {
						$item['control']['items'] = new Nette\DI\Statement('@locationRepositoryAccessor');


						$field = new Extras\Config\Field($name, $item['label'], $item['control']['type']);
						$field->setOptions('control', $item['control']);

						// parsovanie validacnych pravidiel
						/*
						if (isset($field->validation)) {
							foreach ((array)$field->validation as $params) {
								$method = current($params);
								unset($params[key($params)]);
								$control->addValidator($method, iterator_to_array($params));
							}
						}
						*/
					}

					$generator->addSetup('addItem', array(new Nette\DI\Statement($factory), $field));
				}
			}
		}

	}


	/**
	 * Register extension to compiler.
	 *
	 * @param Nette\Config\Configurator
	 * @param string
	 */
	public static function register(Nette\Config\Configurator $configurator, $name = 'presenter') {
		$class = get_called_class();
		$configurator->onCompile[] = function(Nette\Config\Configurator $configurator, Nette\Config\Compiler $compiler) use($class, $name) {
			$compiler->addExtension($name, new $class);
		};
	}
}