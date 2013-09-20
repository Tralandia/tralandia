<?php

namespace Extras\Config;

use Nette, Extras;

class PresenterExtension extends Nette\DI\CompilerExtension
{
	public $defaults = array(
		'configsDir' => '%appDir%/configs/presenters'
	);

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		foreach (Nette\Utils\Finder::findFiles('*.neon')->in($config['configsDir']) as $path => $file) {
			$builder->addDependency($path);
			$prefix = substr($file->getBasename(), 0, -5);
			$params = Nette\Utils\Neon::decode(file_get_contents($path));

			if (!isset($params['entity'])) {
				throw new \Exception("Subor $path musi obsahovat sekciu entity!");
			}

			$settings = isset($params['parameters']) ? $params['parameters'] : array();
			$entityClass = isset($params['entity']) ? $params['entity'] : null;
			$this->normalizeVariables($settings);

			$settings = $builder->addDefinition($this->prefix($prefix . '.settings'))
				->setClass('Extras\Presenter\Settings', array($settings, $entityClass, '@container'))
				->setAutowired(FALSE);

			$builder->addDefinition($this->prefix($prefix))
				->setClass('Nette\DI\Extensions\NetteAccessor', array('@container', $this->prefix($prefix)));

			$generator = $builder->addDefinition($this->prefix($prefix . '.formGenerator'))
				->setClass('Extras\FormMask\Generator', array(new Nette\DI\Statement('Extras\FormMask\Mask')))
				->setAutowired(FALSE);

			$form = $builder->addDefinition($this->prefix($prefix . '.form'))
				->setClass('Extras\FormMask\FormFactory', array($generator));

			$gridFactory = $builder->addDefinition($this->prefix($prefix . '.gridFactory'));

			if(isset($params['gridFactory']) && is_string($params['gridFactory'])) {
				$gridFactory->setFactory($params['gridFactory']);
			}

//			$grid = $builder->addDefinition($this->prefix($prefix . '.grid'))
//				->setClass('AdminModule\Components\GridFactory');
//
//			if (isset($params['grid']) && is_array($params['grid'])) {
//				if (isset($params['grid'])) {
//					$grid->addSetup('setParameters', array($params['grid']));
//				}
//				if (isset($params['grid']) && isset($params['grid']['class'])) {
//					$grid->addSetup('setClass', array($params['grid']['class']));
//				}
//			} elseif(isset($params['grid']) && is_string($params['grid'])) {
//				$grid->addSetup('setClass', array($params['grid']));
//			}


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
		$configurator->onCompile[] = function(Nette\Configurator $configurator, Nette\DI\Compiler $compiler) use($class, $name) {
			$compiler->addExtension($name, new $class);
		};
	}
}
