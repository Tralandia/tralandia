<?php

namespace Extras\FormMask\Items;

use Nette, Extras, VojtechDobes;

/**
 * Map polozka masky
 */
class Map extends Base {

	/** @var Extras\Models\Entity\IEntity */
	protected $entity;

	/**
	 * @param string
	 * @param string
	 * @param Extras\Models\Entity\IEntity
	 */
	public function __construct($name, $label, Extras\Models\Entity\IEntity $entity) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->setValueGetter(new Extras\Callback(function() use ($entity) {
			return array(
				'lat' => $entity->latitude->toFloat(),
				'lng' => $entity->longitude->toFloat()
			);
		}));
		$this->setValueSetter(new Extras\Callback(function($value) use ($entity) {
			$entity->latitude = new Extras\Types\Latlong($value->getLat());
			$entity->longitude = new Extras\Types\Latlong($value->getLng());
		}));
	}

	/**
	 * Prida polozku do formulara
	 * @param Nette\Forms\Form
	 * @return Nette\Forms\IControl
	 */
	public function extend(Nette\Forms\Form $form) {
		return $form->addGpsPicker($this->getName(), $this->getLabel(), array(
			'type' => VojtechDobes\NetteForms\GpsPicker::TYPE_ROADMAP,
			'zoom' => 7,
			'size' => array('x' => 300, 'y' => 300)
		))->setDefaultValue($this->getValue());
	}
}