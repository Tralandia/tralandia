<?php

namespace Extras\FormMask\Items;

use Nette, Extras, VojtechDobes;

/**
 * Map polozka masky
 */
class Map extends Base {

	/** @var \Entity\BaseEntity */
	protected $entity;

	/**
	 * @param string
	 * @param string
	 * @param \Entity\BaseEntity
	 */
	public function __construct($name, $label, \Entity\BaseEntity $entity) {
		$this->name = $name;
		$this->label = $label;
		$this->entity = $entity;
		$this->setValueGetter(new Extras\Callback(function() use ($entity) {
			return array(
				'lat' => $entity->latitude instanceof Extras\Types\Latlong ? $entity->latitude->toFloat() : 0,
				'lng' => $entity->longitude instanceof Extras\Types\Latlong ? $entity->longitude->toFloat() : 0
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
