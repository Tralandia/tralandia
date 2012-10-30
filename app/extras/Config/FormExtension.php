<?php

namespace Extras\Config;

use Nette;

class FormExtension extends Nette\Config\CompilerExtension
{
	public $defaults = array();

	public $formFields = array(
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

	public function loadConfiguration() {
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
//debug($config);

		foreach ($config as $name => $item) {
			//$name = substr($name, strpos($name, '.')+1);
			debug($name, $item);

			$builder->addDefinition($this->prefix($name))
				->setClass($this->formFields[$item['control']['type']], array($name, $item['label']));
		}


//		$builder->addDefinition($this->prefix(static::ENTITY_MANAGERS_PREFIX))
//			->setClass('Nette\DI\NestedAccessor', array('@container', $this->prefix(static::ENTITY_MANAGERS_PREFIX)));
//$builder->addDefinition($this->prefix('articles'))
//    ->setClass('MyBlog\ArticlesModel', array('@connection'));

		
	}
}