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

	public function actionDefault($id) {

		$mainEntity = $this->getEntityReflection($id);
		$newClass = new PhpGenerator\ClassType('User');
		
		$newClass->extends[] = 'BaseEntity';
		$construct = $newClass->addMethod('__construct');

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

				if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE) {

					if($targetedEntityPropery == NULL) {												// Many To One Uni
						$this->addManyToOneUni($newClass, $property);
					}

				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {

					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) { 	// Many To Many Bi
						$this->addManyToManyBi($newClass, $property, $targetedEntityPropery);
					}

				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_MANY){
					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_ONE) { 	// One To Many Bi
						$this->addMethod('get',$newClass, $property, $targetedEntityPropery);
						$this->addMethod('add2',$newClass, $property, $targetedEntityPropery);
						$this->addMethod('remove2',$newClass, $property, $targetedEntityPropery);
					}

				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE){
					
				}
				//debug($association);
			} else {

			}
			//break;
			//debug($annotations);
		}

		$this->fillConstruct($construct);

		$this->template->newClass = $newClass;
	}

	public function toSingular($name) { // @todo
		if(Strings::endsWith($name, 's')) {
			$name = substr($name, 0 , -1);
		} else if (Strings::endsWith($name, 'ies')) {
			$name = substr($name, 0 , -3).'y';
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
		$methodPrefix = $type;
		if(in_array($type, array('add', 'add2', 'remove', 'remove2'))) {
			$snippet->type = 1;
			$snippet->returnThis = TRUE;
			if($type == 'add') {
				$snippet->var = 'add';
				$snippet->var2 = TRUE;
			} else if ($type == 'add2') {
				$methodPrefix = 'add';
				$snippet->var = 'add';
				$snippet->var2 = FALSE;
			} else if ($type == 'remove') {
				$snippet->var = 'removeElement';
				$snippet->var2 = TRUE;
			} else if ($type == 'remove2') {
				$methodPrefix = 'remove';
				$snippet->var = 'removeElement';
				$snippet->var2 = FALSE;
			}
		} else if($type == 'set') {
			$snippet->type = 2;
			$snippet->returnThis = TRUE;
		} else if($type == 'get') {
			$snippet->type = 3;
			$snippet->returnThis = FALSE;
		}
		$method = $newClass->addMethod($methodPrefix.$property->singularFu);

		$parameter = $property->singular;
		if($snippet->type == 2) {
			$firstParameter = $method->addParameter($property->singular, NULL);
		} else {
			$firstParameter = $method->addParameter($property->singular);
		}
		$firstParameter->typeHint = $property->targetEntity;

		$body = array();
		if($snippet->type == 1) {
			$method->documents[] = '@param Entity';
			$body[] = sprintf('if(!$this->%s->contains($%s)) {', $property->name, $parameter);
			$body[] = sprintf('%s$this->%s->%s($%s);', "\t", $property->name, $snippet->var, $parameter);
			$body[] = '}';			
			if($snippet->var2) {
				$body[] = sprintf('$%s->%s%s($this);', $parameter, $type, $tagetPropery->singularFu);
			} else {
				if($methodPrefix == 'add') {
					$body[] = sprintf('$%s->set%s($this);', $parameter, $tagetPropery->nameFu);
				} else {
					$body[] = sprintf('$%s->set%s();', $parameter, $tagetPropery->nameFu);					
				}
			}
		} else if($snippet->type == 2) {
			$method->documents[] = '@param Entity|NULL';
			$body[] = sprintf('$this->%s = $%s;', $property->name, $property->name);
		} else if($snippet->type == 3) {
			$method->documents[] = '@param Entity';
			$method->documents[] = '@return Collection';
			$body[] = sprintf('return $this->%s;', $property->name, $property->name);
		}

		if($snippet->returnThis) {
			$method->documents[] = '@return this';
			$body[] = '';
			$body[] = 'return $this;';
		}

		foreach ($body as $key => $val) {
			$method->addBody($val);
		}		
	}

	public function fillConstruct($construct) {
		$body = array();
		$body[] = 'parent::__construct();';
		foreach ($body as $key => $val) {
			$construct->addBody($val);
		}		
	}

	public function addOneToOneBi() {

	}

	public function addManyToOneUni($newClass, $property) {
		$this->addMethod('set', $newClass, $property);
	}

	public function addManyToManyBi($newClass, $property, $tagetPropery) {
		$this->addMethod('get', $newClass, $property, $tagetPropery);
		$this->addMethod('add', $newClass, $property, $tagetPropery);
		$this->addMethod('remove', $newClass, $property, $tagetPropery);
	}

}
