<?php

namespace Extras\Config;

use Nette, Extras;

class PresenterExtension extends Nette\Config\CompilerExtension
{
	public $defaults = array(
		'configsDir' => '%appDir%/configs/presenters'
	);

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		foreach (Nette\Utils\Finder::findFiles('*.neon')->in($config['configsDir']) as $path => $file) {
			$prefix = substr($file->getBasename(), 0, -5);
			$params = Nette\Utils\Neon::decode(file_get_contents($path));
			$settings = isset($params['parameters']) ? $params['parameters'] : array();
			$this->normalizeVariables($settings);

			$settings = $builder->addDefinition($this->prefix($prefix . '.settings'))
				->setClass('Extras\Presenter\Settings', array($settings, '@container'))
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix($prefix))
				->setClass('Nette\Config\Extensions\NetteAccessor', array('@container', $this->prefix($prefix)));

			$generator = $builder->addDefinition($this->prefix($prefix . '.formGenerator'))
				->setClass('Extras\FormMask\Generator', array(new Nette\DI\Statement('Extras\FormMask\Mask')))
				->setAutowired(FALSE);

			$form = $builder->addDefinition($this->prefix($prefix . '.form'))
				->setClass('Extras\FormMask\FormFactory', array($generator));

			if (isset($params['form']) && isset($params['form']['fields'])) {
				foreach ($params['form']['fields'] as $name => $item) {
					$factory = '@item' . ucfirst($item['control']['type']) . 'Factory';

					if (isset($item['control']['type'])) {
						$this->findServices($item['control']);
						$field = $builder->addDefinition($this->prefix($prefix . '.formGenerator.' . $name))
							->setClass('Extras\Config\Field', array($name, $item['label'], $item['control']['type']))
							->addSetup('setOptions', array('control', $item['control']))
							->setShared(FALSE)->setAutowired(FALSE);
					}
					$generator->addSetup('addItem', array(new Nette\DI\Statement($factory), $field));
				}
			}
		}
	}

	/**
	 * Vyhlada vsetky pripadne service a nahradi ich
	 * @param array
	 */
	private function findServices(&$params) {
		foreach ($params as $key => $value) {
			if ($params[$key] instanceof Nette\Utils\NeonEntity) {
				if (is_array($params[$key]->attributes)) {
					$this->findServices($params[$key]->attributes);
				}
				$params[$key] = new Nette\DI\Statement($params[$key]->value, $params[$key]->attributes);
			} elseif (is_array($params[$key])) {
				$this->findServices($params[$key]);
			}
		}
	}

	/**
	 * Vyhlada vsetky % a nahradi $
	 * @param array
	 */
	private function normalizeVariables(&$params) {
		foreach ($params as $key => $value) {
			$params[$key] = str_replace('%', '$', $value);
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