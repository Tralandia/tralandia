<?php

namespace Extras\Config;

use Nette;

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

			$form = $builder->addDefinition($this->prefix($prefix . '.form'))
				->setClass('Nette\Application\UI\Form', array(null))
				->setShared(FALSE)->setAutowired(FALSE);

			if (isset($params['form']) && isset($params['form']['fields'])) {
				foreach ($params['form']['fields'] as $name => $item) {
					$factory = '@item' . ucfirst($item['control']['type']) . 'Factory';
					debug($name, $this->formFields[$item['control']['type']], $item['control']['type']);
//$b = new Nette\DI\Statement($factory);
//debug($b->create());
//					$a = $builder->addDefinition($this->prefix($prefix . '.form.' . $name))
//						->setClass(new Nette\DI\Statement($factory));

//itemPhraseFactory

//					$form->addSetup('addComponent', array($a, $name));
//					debug($a);
					//$form->addSetup();


				}
			}
		}

/*
		//$this->compiler->parseServices($builder, $this->loadFromFile(APP_DIR . '/configs/config.neon'));

		foreach ($config as $section => $items) {
			$form = $builder->addDefinition($this->prefix($section))
				->setClass('Nette\Application\UI\Form', array(null));

			foreach ($items as $name => $item) {
				debug($name);
				$a = $builder->addDefinition($this->prefix($name))
					->setClass($this->formFields[$item['control']['type']]);

				debug($a);
				//$form->addSetup();


			}
		}

//		$builder->addDefinition($this->prefix(''))
//			->setClass('MyBlog\Components\ArticlesList', array($this->prefix('@articles')))
//			//->addSetup('setPostsPerPage', $config['postsPerPage'])
//			->setShared(FALSE)->setAutowired(FALSE); // ze služby se stane továrnička


		foreach ($config as $name => $item) {
			//$name = substr($name, strpos($name, '.')+1);
			//debug($name, $item);

			//addSetup

			//debug($this->formFields[$item['control']['type']], array($name, $item['label']));

			//$builder->addDefinition($this->prefix($name))
			//	->setClass($this->formFields[$item['control']['type']], array($name, $item['label']));
		}


//		$builder->addDefinition($this->prefix(static::ENTITY_MANAGERS_PREFIX))
//			->setClass('Nette\DI\NestedAccessor', array('@container', $this->prefix(static::ENTITY_MANAGERS_PREFIX)));
//$builder->addDefinition($this->prefix('articles'))
//    ->setClass('MyBlog\ArticlesModel', array('@connection'));
*/
		
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