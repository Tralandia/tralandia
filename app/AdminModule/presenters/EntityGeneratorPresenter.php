<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils\PhpGenerator;
use Nette\Utils\Strings;
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
			//debug($property);
			if($property->isCollection) {

				$targetEntity = $this->getEntityReflection($property->targetEntity);
				$targetedEntityPropery = NULL;
				if(isset($property->mappedBy)) {
					$targetedEntityPropery = $this->getPropertyInfo($targetEntity->getProperty($property->mappedBy));
				} else if(isset($property->inversedBy)) {
					$targetedEntityPropery = $this->getPropertyInfo($targetEntity->getProperty($property->inversedBy));
				}

				if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE && $targetedEntityPropery == NULL) {
					$this->addManyToOneUni($newClass, $property);
				}else if($property->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {
					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) { // Many To Many Bi
						$this->addManyToManyBi($newClass, $property, $targetedEntityPropery);
					} else if ($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {

					}
				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE){
				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_ONE){
					
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
		if(Strings::endsWith($name, 's')) {
			$name = substr($name, 0 , -1);
		}
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

			if(array_key_exists('targetEntity', $annotations[$association][0])) {
				$return['targetEntity'] = $annotations[$association][0]['targetEntity'];
				if(!Strings::startsWith($return['targetEntity'], 'Entities')) {
					$class = $this->getEntityReflection($property->class);
					$classNamespace = $class->getNamespaceName();
					$return['targetEntity'] = $classNamespace.'\\'.$return['targetEntity'];
				}
			}

			if(array_key_exists('mappedBy', $annotations[$association][0])) {
				$return['mappedBy'] = $annotations[$association][0]['mappedBy'];
			}

			if(array_key_exists('inversedBy', $annotations[$association][0])) {
				$return['inversedBy'] = $annotations[$association][0]['inversedBy'];
			}

		} else {
			$return['isCollection'] = FALSE;
		}

		$return['name'] = $property->name;
		$return['nameFu'] = Strings::firstUpper($return['name']);

		$return['class'] = $property->class;

		$return['singular'] = $this->toSingular($property->name);
		$return['singularFu'] = Strings::firstUpper($return['singular']);

		return \Nette\ArrayHash::from($return);
	}

	public function addMethod($type, $newClass, $property, $tagetPropery = NULL) {
		$snippet = \Nette\ArrayHash::from(array());
		if(in_array($type, array('add', 'remove'))) {
			$snippet->type = 1;
			if($type == 'add') {
				$snippet->var = 'add';
			} else {
				$snippet->var = 'removeElement';
			}
		} else if($type == 'set') {
			$snippet->type = 2;
		}

		$method = $newClass->addMethod($type.$property->singularFu);

		$parameter = $property->singular;
		$firstParameter = $method->addParameter($property->singular);
		$firstParameter->typeHint = $property->targetEntity;

		$body = array();
		if($snippet->type == 1) {
			$body[] = sprintf('if(!$this->%s->contains($%s)) {', $property->name, $parameter);
			$body[] = sprintf('%s$this->%s->%s($%s);', "\t", $property->name, $snippet->var, $parameter);
			$body[] = sprintf('%s$%s->%s%s($this);', "\t", $parameter, $type, $tagetPropery->singularFu);
			$body[] = '}';			
		} else if($snippet->type == 2) {
			$body[] = sprintf('$this->%s = $%s;', $property->name, $property->name);
		}
		$body[] = '';
		$body[] = 'return $this;';

		foreach ($body as $key => $val) {
			$method->addBody($val);
		}		
	}

	public function addOneToOneBi() {

	}

	public function addManyToOneUni($newClass, $property) {
		$this->addMethod('set', $newClass, $property);
	}

	public function addManyToManyBi($newClass, $property, $tagetPropery) {
		$this->addMethod('add', $newClass, $property, $tagetPropery);
		$this->addMethod('remove', $newClass, $property, $tagetPropery);
	}

}
