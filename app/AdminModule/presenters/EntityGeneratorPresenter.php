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
		$newClass = new PhpGenerator\ClassType($mainEntity->name);
		
		$newClass->extends[] = 'BaseEntity';
		$construct = $newClass->addMethod('__construct');
		$collections = array();

		$properties = $mainEntity->getProperties();
		foreach ($properties as $property) {
			if(in_array($property->name, array('id', 'created', 'updated'))) continue;

			$property = $this->getPropertyInfo($property);

			if($property->isCollection) {

				$targetEntity = $this->getEntityReflection($property->targetEntity);

				$targetedEntityPropery = NULL;
				if(isset($property->mappedBy)) {
					$targetedEntityPropery = $this->getPropertyInfo($targetEntity->getProperty($property->mappedBy));
				} else if(isset($property->inversedBy)) {
					$targetedEntityPropery = $this->getPropertyInfo($targetEntity->getProperty($property->inversedBy));
				}

				if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE) {

					if(isset($property->mappedBy)) {
						$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);						
					} else if(isset($property->inversedBy)) {
						$this->addMethod('get', $newClass, $property, $targetedEntityPropery);
					} else {
						$this->addMethod('set', $newClass, $property, $targetEntity->name);
					}


				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {
					$collections[] = $property;
					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_MANY) { 	// Many To Many Bi
						
						if(isset($property->mappedBy)) {
							$this->addMethod('get', $newClass, $property, $targetedEntityPropery);
							$this->addMethod('add', $newClass, $property, $targetedEntityPropery);
							$this->addMethod('remove', $newClass, $property, $targetedEntityPropery);							
						} else if(isset($property->inversedBy)) {							
							$this->addMethod('get', $newClass, $property, $targetedEntityPropery);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);
						}

					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);
					}

				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_MANY){
					
					if($targetedEntityPropery->association == ORM\ClassMetadataInfo::MANY_TO_ONE) { 	// One To Many Bi
						$this->addMethod('get', $newClass, $property, $targetedEntityPropery);
						$this->addMethod('add2', $newClass, $property, $targetedEntityPropery);
						$this->addMethod('remove2', $newClass, $property, $targetedEntityPropery);
					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);
					}

				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_ONE){
					
					if($targetedEntityPropery == NULL) {												// One To One Uni
						if($targetEntity->name == 'Entities\Dictionary\Phrase') {
							$this->addMethod('setPhrase', $newClass, $property, $targetEntity->name);
							$this->addMethod('get', $newClass, $property, $targetEntity->name);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);
						}
					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityPropery);
					}

				} else {
					
					$this->addMethod('todo', $newClass, $property, $targetEntity->name);

				}

			} else {
				$this->addMethod('set', $newClass, $property, $property->type);
				$this->addMethod('get2', $newClass, $property, $property->type);
			}

		}

		$this->fillConstruct($construct, $collections);

		$this->template->newClass = $newClass;
	}

	public function toSingular($name) { // @todo
		if(in_array($name, array('status'))) return $name;
		if(Strings::endsWith($name, 'ies')) {
			$name = substr($name, 0 , -3).'y';
		} else if (Strings::endsWith($name, 's')) {
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
			$return['type'] = $annotations['ORM\Column'][0]['type'];
			if(!in_array($return['type'], array('integer', 'string', 'boolean'))) {
				$return['type'] = '\Extras\Types\\'.Strings::firstUpper($return['type']);
			}
			if(array_key_exists('nullabale', $annotations['ORM\Column'][0])) {
				$return['isNullable'] = $annotations['ORM\Column'][0]['nullable'];
			} else {
				$return['isNullable'] = NULL;
			}
		}

		$return['name'] = $property->name;
		$return['nameFu'] = Strings::firstUpper($return['name']);

		$return['class'] = $property->class;

		$return['singular'] = $this->toSingular($property->name);
		$return['singularFu'] = Strings::firstUpper($return['singular']);

		return \Nette\ArrayHash::from($return);
	}

	public function addMethod($type, $newClass, $property, $tagetPropery) {
		if(is_string($tagetPropery)) {
			$tagetProperyClass = $tagetPropery;
		} else if($tagetPropery instanceof \Nette\ArrayHash){
			$tagetProperyClass = $tagetPropery->class;
		} else {
			debug($type, $newClass, $property, $tagetPropery);
			$tagetProperyClass = '#todo';
		}

		$snippet = \Nette\ArrayHash::from(array());
		$methodName = \Nette\ArrayHash::from(array(
			'prefix' => $type,
			'name' => $property->singularFu,
		));

		if(in_array($type, array('add', 'add2', 'remove', 'remove2'))) {
			$snippet->type = 1;
			$snippet->returnThis = TRUE;
			if($type == 'add') {
				$snippet->var = 'add';
				$snippet->var2 = TRUE;
			} else if ($type == 'add2') {
				$methodName->prefix = 'add';
				$snippet->var = 'add';
				$snippet->var2 = FALSE;
			} else if ($type == 'remove') {
				$snippet->var = 'removeElement';
				$snippet->var2 = TRUE;
			} else if ($type == 'remove2') {
				$methodName->prefix = 'remove';
				$snippet->var = 'removeElement';
				$snippet->var2 = FALSE;
			}
		} else if($type == 'set') {
			$snippet->type = 2;
			$snippet->returnThis = TRUE;
		} else if(in_array($type, array('get', 'get2'))) {
			$methodName->prefix = 'get';
			$methodName->name = $property->nameFu;
			$snippet->type = 3;
			$snippet->returnThis = FALSE;
		} else if($type == 'todo') {
			$methodName->prefix = 'todo';
			$methodName->name = $property->nameFu;
			$snippet->type = 4;
			$snippet->returnThis = FALSE;
		} else if($type == 'setPhrase') {
			$methodName->prefix = 'set';
			$snippet->type = 5;
			$snippet->returnThis = TRUE;
		} else if($type == 'addInverse') {
			$methodName->prefix = 'add';
			$snippet->type = 6;
			$snippet->returnThis = FALSE;
		} else {
			throw new \Exception("Neblbni!", 1);
		}

		$method = $newClass->addMethod($methodName->prefix.$methodName->name);

		$parameter = $property->singular;
		if($snippet->type == 2) {
			$firstParameter = $method->addParameter($property->singular, NULL);
		} else if($snippet->type == 3 || $snippet->type == 4){			

		} else {
			$firstParameter = $method->addParameter($property->singular);
			$firstParameter->typeHint = $property->targetEntity;
		}

		$body = array();
		if($snippet->type == 1) {
			$method->documents[] = sprintf('@param %s', $tagetProperyClass);
			$body[] = sprintf('if(!$this->%s->contains($%s)) {', $property->name, $firstParameter->name);
			$body[] = sprintf('%s$this->%s->%s($%s);', "\t", $property->name, $snippet->var, $firstParameter->name);
			$body[] = '}';			
			if($snippet->var2 === TRUE) {
				//$body[] = sprintf('$%s->%s%s($this);', $parameter, $type, $tagetPropery->singularFu);
			} else if($snippet->var2 === FALSE){
				if($methodName->prefix == 'add') {
					//$body[] = sprintf('$%s->set%s($this);', $parameter, $tagetPropery->nameFu);
				} else {
					//$body[] = sprintf('$%s->set%s();', $parameter, $tagetPropery->nameFu);					
				}
			}
		} else if($snippet->type == 2) {
			$method->documents[] = sprintf('@param %s|NULL', $tagetProperyClass);
			$body[] = sprintf('$this->%s = $%s;', $property->name, $property->name);
		} else if($snippet->type == 3) {
			$method->documents[] = sprintf('@return %s', $tagetProperyClass);
			$body[] = sprintf('return $this->%s;', $property->name, $property->name);
		} else if($snippet->type == 4) {
			$method->documents[] = sprintf('@todo %s', $property->name);
		} else if($snippet->type == 5) {
			$method->documents[] = sprintf('@param %s', $tagetProperyClass);
			$body[] = sprintf('$this->%s = $%s;', $property->name, $firstParameter->name);
			$body[] = sprintf('$%s->setEntityId($this->getId());', $firstParameter->name);
		} else if($snippet->type == 6) {
			$method->documents[] = sprintf('@param %s', $tagetProperyClass);
			$body[] = sprintf('return $%s->add%s($this);', $firstParameter->name, $property->singularFu);
		}

		if($snippet->returnThis) {
			$method->documents[] = sprintf('@return %s', $property->class);
			$body[] = '';
			$body[] = 'return $this;';
		}

		foreach ($body as $key => $val) {
			$method->addBody($val);
		}

		return $method;	
	}


	public function fillConstruct($construct, $collections) {
		$body = array();
		$body[] = 'parent::__construct();'; //@todo comstruct nema ziadne parametre?
		$body[] = '';
		foreach ($collections as $key => $val) {
			$body[] = sprintf('$this->%s = new \Doctrine\Common\Collections\ArrayCollection;', $val->name);
		}
		foreach ($body as $key => $val) {
			$construct->addBody($val);
		}		
	}

}
