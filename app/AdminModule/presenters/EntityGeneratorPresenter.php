<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils\PhpGenerator;
use Nette\Utils\Stings;
use Doctrine\ORM\Mapping as ORM;

class EntityGeneratorPresenter extends BasePresenter {

	protected $entitiesReflection = array();

	public function beforeRender() {
		parent::beforeRender();
		$this->setView('default');
	}

	public function actionTest() {
		$mainEntity = $this->getEntityReflection('Entities\User\User');
		$newClass = new PhpGenerator\ClassType('User');
		
		$newClass->extends[] = 'BaseEntity';

		$properties = $mainEntity->getProperties();
		foreach ($properties as $property) {
			$property = $this->getPropertyInfo($property);
			//debug($annotations);
			if($property->isCollection) {
				$targetEntity = $this->getEntityReflection($property->targetEntity);
				$targetedEntityPropery = $this->getPropertyInfo($targetEntity->getProperty($property->mappedBy));
				debug($targetedEntityPropery);
				if($property->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {
					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) { // Many To Many Uni

					} else if ($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {

					}
					$method = $newClass->addMethod('add'.$this->toSingular($property->name));
					$firstParameter = $method->addParameter('language');
					$firstParameter->typeHint = '\Entities\Language\Language';
					$method->addBody('$foo->bar($test,$annotations);');
				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE){
					
				}
				//debug($association);
			} else {

			}
			//break;
			//debug($annotations);
		}
		$this->template->newClass = $newClass;
	}

	public function toSingular($name) {
		return $name;
	}

	public function getEntityReflection($name) {
		if(!array_key_exists($name, $this->entitiesReflection)) {
			$this->entitiesReflection[$name] = new Reflection\ClassType($name);
		}
		return $this->entitiesReflection[$name];
	}

	public function getPropertyInfo(Reflection\Property $property) {
		$return = array();
		$annotations = $property->getAnnotations();
		if($annotations['var'][0] == 'Collection') {
			$return['isCollection'] = TRUE;
			if(array_key_exists('ORM\ManyToMany', $annotations)) {
				$association = 'ORM\ManyToMany';
				$return['association'] = ORM\ClassMetadataInfo::MANY_TO_MANY;
			} else if(array_key_exists('ORM\ManyToOne', $annotations)) {
				$association = 'ORM\ManyToOne';
				$return['association'] = ORM\ClassMetadataInfo::MANY_TO_ONE;			
			} else if(array_key_exists('ORM\OneToMany', $annotations)) {
				$association = 'ORM\OneToMany';
				$return['association'] = ORM\ClassMetadataInfo::ONE_TO_MANY;			
			} else if(array_key_exists('ORM\OneToOne', $annotations)) {
				$association = 'ORM\OneToOne';
				$return['association'] = ORM\ClassMetadataInfo::ONE_TO_ONE;			
			} else {
				$return['association'] = 0;			
			}
			if(array_key_exists('mappedBy', $annotations[$association][0])) {
				$return['mappedBy'] = $annotations[$association][0]['mappedBy'];
			}
			if(array_key_exists('targetEntity', $annotations[$association][0])) {
				$return['targetEntity'] = $annotations[$association][0]['targetEntity'];
			}
			if(array_key_exists('inversedBy', $annotations[$association][0])) {
				$return['inversedBy'] = $annotations[$association][0]['inversedBy'];
			}
		} else {
			$return['isCollection'] = FALSE;
		}
		$return['name'] = $property->name;
		$return['singular'] = $this->toSingular($property->name);

		return \Nette\ArrayHash::from($return);
	}

}
