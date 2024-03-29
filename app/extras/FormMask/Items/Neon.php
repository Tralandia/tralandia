<?php
namespace Extras\FormMask\Items;

use Nette, Extras;

/**
 * Neon polozka masky
 * 
 * @author Branislav Vaculčiak
 */
class Neon extends Text {

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		$reflection = Extras\Reflection\Entity\ClassType::from($this->entity);
		if ($reflection->getProperty($this->name)->hasAnnotation('EA\Json')) {
			$structure = $reflection->getProperty($this->name)->getAnnotation('EA\Json')->structure;
			$structure = Nette\Utils\Neon::decode($structure);
		}
		if (!isset($structure) || !$structure) {
			$structure = array();
		}

		$control = $form->addAdvancedNeon($this->getName(), $this->getLabel(), $structure);
		$control->setDefaultValue($this->getValue())
			->setDisabled($this->disabled);

		foreach ($this->validators as $validator) {
			call_user_func_array(array($control, $validator->method), $validator->params);
		}

		return $control;
	}
}