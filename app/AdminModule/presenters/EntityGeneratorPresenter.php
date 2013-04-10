<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils\PhpGenerator;
use Nette\Utils\Strings;
use Nette\Utils\Finder;
use Doctrine\ORM\Mapping as ORM;

class EntityGeneratorPresenter extends BasePresenter {

	public static $skipTypeHintIn = array('integer', 'string', 'boolean', 'decimal', 'json', 'slug', 'float');

	protected $entitiesReflection = array();

	public $skipMethods = array();

	// public function beforeRender() {
	// 	parent::beforeRender();
	// 	$this->setView('default');
	// }

	public function actionDefault($id) {
		$tempId = $id;
		$id = str_replace('-', '\\', $id);
		$entityDir = APP_DIR . '/models/Entity/';
		$menu = array();
		$lastFolderName = NULL;
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {
			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);
			$folderNameTemp = explode('\\', $entityNameTemp, 3);
			array_shift($folderNameTemp);
			$folderName = array_shift($folderNameTemp);
			if($lastFolderName != $folderName && array_shift($folderNameTemp)) {
				$lastFolderName = $folderName;
				$menu[] = array(
					'link' => $this->link('EntityGenerator:forceAll', array('id' => 'Entity-'.$folderName)),
					'name' => str_replace('Entity\\', '', $folderName).' < -- pregenerovat subory'
				);
			}
			$menu[] = array(
				'link' => $this->link('EntityGenerator:default', array('id' => str_replace('\\', '-', $entityNameTemp))),
				'name' => str_replace('Entity\\', '', $entityNameTemp)
			);
		}

		$mainEntityReflector = $this->getEntityReflection($id);
		$newClass = $this->generateNewClass($mainEntityReflector);

		list($a, $nameTemp) = explode('-', $tempId, 2);
		$filename = $entityDir.str_replace('-', '/', $nameTemp).'.php';
		$newFileContent = $this->generateNewCode($filename, $newClass);

		if(!$newFileContent) {
			$newFileContent = "V subore:\n$id\nchyba riadok:\n//@entity-generator-code\ndopln to tam a refresni stranku";
		}

		if($this->getParameter('force') == 1) {
			$this->writeNewCode($filename, $newFileContent);

			$this->flashMessage('done');
			$this->redirect('this', array('force' => NULL));
		}

		$this->template->menu = $menu;
		$this->template->newClass = $newClass;
		$this->template->newFileContent = $newFileContent;
	}

	public function actionForceAll($id) {
		$id = str_replace(array('Entity', '-'), array('', '/'), $id);
		$entityDir = APP_DIR . '/models/Entity/'.$id;

		$menu = array();
		$messageSuccess = array();
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {
			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('//', '/', '.php'), array('\\', '\\', ''), $entityNameTemp);

			$filename = $key;
			
			$mainEntityReflector = $this->getEntityReflection($entityNameTemp);
			$newClass = $this->generateNewClass($mainEntityReflector);

			$newFileContent = $this->generateNewCode($filename, $newClass);
			if($newFileContent) {
				$this->writeNewCode($filename, $newFileContent);
				$messageSuccess[] = $entityNameTemp;
			} else {
				$message = "V subore: $entityNameTemp chyba riadok: //@entity-generator-code";
				$this->flashMessage($message);
				d($message);
			}
		}
		if(count($messageSuccess)) {
			$messageSuccess = "Pregeneroval som: ".implode('; ', $messageSuccess);
		} else {
			$messageSuccess = "Pregeneroval som: NIC";
		}
		$this->flashMessage($messageSuccess);
		//d($messageSuccess);
		$this->redirect('EntityGenerator:default', array('id' => 'Entity-Currency'));
	}

	public function generateNewClass($mainEntity) {
		$ann = $mainEntity->getAnnotations();
		if(array_key_exists('EA\Generator', $ann)) {
			$skip = $ann['EA\Generator'][0]->skip;
			$this->skipMethods = \Nette\Utils\Neon::decode($skip);
		} else {
			$this->skipMethods = array();
		}

		$newClass = new \Nette\PhpGenerator\ClassType($mainEntity->name);

		$construct = $newClass->addMethod('__construct');
		$collections = array();

		$properties = $mainEntity->getProperties();
		foreach ($properties as $property) {
			if(in_array($property->name, array('nestedLeft', 'nestedRight', 'nestedRoot'))) continue;
			if(in_array($property->name, array('id', 'created', 'updated', 'oldId')) && $mainEntity->name != 'Entity\BaseEntity') continue;
			if(in_array($property->name, array('details')) && $mainEntity->name != 'Entity\BaseEntityDetails') continue;

			$property = $this->getPropertyInfo($property);
			if ($property === NULL) continue;

			if($property->isCollection) {

				$targetEntity = $this->getEntityReflection($property->targetEntity);

				$targetedEntityProperty = NULL;
				if(isset($property->mappedBy)) {
					$targetedEntityProperty = $this->getPropertyInfo($targetEntity->getProperty($property->mappedBy));
				} else if(isset($property->inversedBy)) {
					$targetedEntityProperty = $this->getPropertyInfo($targetEntity->getProperty($property->inversedBy));
				}

				if($property->association == ORM\ClassMetadataInfo::MANY_TO_ONE) {

					if(isset($property->mappedBy)) {
						$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
					} else if(isset($property->inversedBy)) {
						$this->addMethod('set', $newClass, $property, $targetedEntityProperty);
						$this->addMethod('unset', $newClass, $property, $targetedEntityProperty);
						$this->addMethod('get2', $newClass, $property, $targetedEntityProperty);
					} else {
						$this->addMethod('set', $newClass, $property, $targetEntity->name);
						$this->addMethod('unset', $newClass, $property, $targetEntity->name);
						$this->addMethod('get2', $newClass, $property, $targetEntity->name);
					}


				} else if($property->association == ORM\ClassMetadataInfo::MANY_TO_MANY) {
					$collections[] = $property;

					if($targetedEntityProperty === NULL) {
						$this->addMethod('add2', $newClass, $property, $targetEntity->name);
						$this->addMethod('remove2', $newClass, $property, $targetEntity->name);
						$this->addMethod('get', $newClass, $property, $targetEntity->name);
					} else if ($targetedEntityProperty->association == ORM\ClassMetadataInfo::MANY_TO_MANY) { // MtM Bi
						
						if(isset($property->mappedBy)) {
							$this->addMethod('add', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('remove', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get', $newClass, $property, $targetedEntityProperty);
						} else if(isset($property->inversedBy)) {
							$this->addMethod('add2', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('remove2', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get', $newClass, $property, $targetedEntityProperty);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
						}

					} else if($targetedEntityProperty->association == ORM\ClassMetadataInfo::ONE_TO_MANY){
						if(isset($property->mappedBy)) {
							$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
						} else if(isset($property->inversedBy)) {					
							$this->addMethod('get', $newClass, $property, $targetedEntityProperty);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
						}
					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
					}

				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_MANY){
					$collections[] = $property;

					if($targetedEntityProperty->association == ORM\ClassMetadataInfo::MANY_TO_ONE) { 	// One To Many Bi
						if(isset($property->mappedBy)) {
							$this->addMethod('add3', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('remove3', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get', $newClass, $property, $targetedEntityProperty);
						} else if(isset($property->inversedBy)) {
							$this->addMethod('add2', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('remove2', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get', $newClass, $property, $targetedEntityProperty);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
						}						
					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
					}

				} else if($property->association == ORM\ClassMetadataInfo::ONE_TO_ONE){
					
					if($targetedEntityProperty == NULL) {												// One To One Uni
						if($targetEntity->name == 'Entity\Dictionary\Phrase') {
							$this->addMethod('setPhrase', $newClass, $property, $targetEntity->name);
							$this->addMethod('get2', $newClass, $property, $targetEntity->name);
						} else {
							$this->addMethod('set', $newClass, $property, $targetEntity->name);
							$this->addMethod('get2', $newClass, $property, $targetEntity->name);
						}
					}else if($targetedEntityProperty->association == ORM\ClassMetadataInfo::ONE_TO_ONE){
						if(isset($property->mappedBy)) {
							$this->addMethod('setMapped', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('unsetMapped', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get2', $newClass, $property, $targetedEntityProperty);
						} else if(isset($property->inversedBy)) {
							$this->addMethod('setInverse', $newClass, $property, $targetedEntityProperty);
							$this->addMethod('get2', $newClass, $property, $targetedEntityProperty);
						} else {
							$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
						}						
					} else {
						$this->addMethod('todo', $newClass, $property, $targetedEntityProperty);
					}

				} else {
					
					$this->addMethod('todo', $newClass, $property, $targetEntity->name);

				}

			} else {
				$this->addMethod('set', $newClass, $property, $property->type);
				//debug($property->isNullable);
				if($property->isNullable) $this->addMethod('unset', $newClass, $property, $property->type);
				$this->addMethod('get2', $newClass, $property, $property->type);
			}

		}

		$this->fillConstruct($construct, $collections);
		return $newClass;
	}

	public function generateNewCode($filename, $newClass) {
		if (!preg_match("/\/[a-zA-Z]+Entity[\/a-zA-Z\.]*$/", $filename)) {
			$filename = str_replace('.php', 'Entity.php', $filename);
		}
		$fileSource = fopen($filename, "r") or die("Could not open file!");
		$data = fread($fileSource, filesize($filename)) or die("Could not read file!");
		fclose($fileSource);
		if($pos = mb_strpos($data, "\t//@entity-generator-code")) {
			$newFileContent = mb_substr($data, 0, $pos);
			$newFileContent .= "\t//@entity-generator-code --- NEMAZAT !!!\n\n";
			$newFileContent .= "\t/* ----------------------------- Methods ----------------------------- */";
			foreach ($newClass->methods as $method) {
				$newFileContent .= $this->template->indent("\t\n".$method."\n", 1);
			}
			$newFileContent .= '}';
			return $newFileContent;
		} else {
			return FALSE;
		}
	}

	public function writeNewCode($filename, $code) {
		// open file 
		$fw = fopen($filename, 'w') or die('Could not open file!');
		// write to file
		// added stripslashes to $newdata
		$fb = fwrite($fw, $code) or die('Could not write to file');
		// close file
		fclose($fw);
		return TRUE;		
	}

	public function toSingular($name) {
		if(in_array($name, array('status', 'address', 'decimalPlaces'))) return $name;

		if($name == 'addresses') return 'address';

		if ($name == "media") {
			$name = "medium";
		} else if(Strings::endsWith($name, 'ies')) {
			$name = substr($name, 0 , -3).'y';
		} else if (Strings::endsWith($name, 's')) {
			$name = substr($name, 0 , -1);
		}

		return $name;
	}

	public function getEntityReflection($name) {
		if($name!=='Entity\BaseEntity' && Strings::endsWith($name, 'Entity')) $name = substr($name, 0, -6);
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
				if(!Strings::startsWith($return['targetEntity'], 'Entity') && !Strings::startsWith($return['targetEntity'], '\Entity')) {
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
			if (!isset($annotations['ORM\Column'])) return NULL;

			$return['type'] = $annotations['ORM\Column'][0]['type'];
			
			if($return['type'] == 'text') $return['type'] = 'string';

			if(!in_array($return['type'], self::$skipTypeHintIn)) {
				$typeTemp = Strings::firstUpper($return['type']);
				if($return['type'] == 'datetime') {
					$return['type'] = '\\DateTime';
				} else {
					$return['type'] = '\Extras\Types\\'.$typeTemp;
				}
			}

			if(array_key_exists('nullable', $annotations['ORM\Column'][0])) {
				$return['isNullable'] = $annotations['ORM\Column'][0]['nullable'];
			} else {
				$return['isNullable'] = NULL;
			}
		}

		$return['name'] = $property->name;
		$return['nameFu'] = Strings::firstUpper($return['name']);

		$return['class'] = $property->class;

		if($return['isCollection'])
			$return['singular'] = $this->toSingular($property->name);
		else 
			$return['singular'] = $property->name;

		$return['singularFu'] = Strings::firstUpper($return['singular']);

		return \Nette\ArrayHash::from($return);
	}

	public function addMethod($type, $newClass, $property, $targetProperty) {
		if(is_string($targetProperty)) {
			if(Strings::startsWith($targetProperty, 'Entity'))
				$targetPropertyClass = '\\'.$targetProperty;
			else 
				$targetPropertyClass = $targetProperty;
		} else if($targetProperty instanceof \Nette\ArrayHash){
			$targetPropertyClass = '\\'.$targetProperty->class;
		} else {
			debug($type, $newClass, $property, $targetProperty);
			$targetPropertyClass = '#todo';
		}

		$snippet = \Nette\ArrayHash::from(array());
		$methodName = \Nette\ArrayHash::from(array(
			'prefix' => $type,
			'name' => $property->singularFu,
		));

		if(in_array($type, array('add', 'add2', 'add3'))) {
			$snippet->type = 1;
			$snippet->returnThis = TRUE;
			if($type == 'add') {
				$snippet->var = 'add';
				$snippet->var2 = TRUE;
			} else if ($type == 'add2') {
				$methodName->prefix = 'add';
				$snippet->var = 'add';
				$snippet->var2 = NULL;
			} else if ($type == 'add3') {
				$methodName->prefix = 'add';
				$snippet->var = 'add';
				$snippet->var2 = FALSE;
			}
		} else if(in_array($type, array('set', 'setMapped', 'setInverse'))) {
			$methodName->prefix = 'set';
			$snippet->type = 2;
			$snippet->returnThis = TRUE;
		} else if(in_array($type, array('get', 'get2'))) {
			$methodName->prefix = 'get';
			$methodName->name = $property->nameFu;
			if($type == 'get2') {
				$snippet->type = 3;
			} else {
				$snippet->type = 7;
			}
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
		} else if(in_array($type, array('unset', 'unsetMapped',))) {
			$methodName->prefix = 'unset';
			$snippet->type = 8;
			$snippet->returnThis = TRUE;
		} else if(in_array($type, array('remove', 'remove2', 'remove3'))) {
			$snippet->type = 9;
			$snippet->returnThis = TRUE;
			if ($type == 'remove') {
				$snippet->var = 'removeElement';
				$snippet->var2 = TRUE;
			} else if ($type == 'remove2') {
				$methodName->prefix = 'remove';
				$snippet->var = 'removeElement';
				$snippet->var2 = NULL;
			} else if ($type == 'remove3') {
				$methodName->prefix = 'remove';
				$snippet->var = 'removeElement';
				$snippet->var2 = FALSE;
			}
		} else {
			throw new \Exception("Neblbni!", 1);
		}

		// d($methodName->prefix.$methodName->name, $this->skipMethods);
		if(in_array($methodName->prefix.$methodName->name, $this->skipMethods)) {
			return FALSE;
		}
		$method = $newClass->addMethod($methodName->prefix.$methodName->name);

		$parameter = $property->singular;
		if(in_array($snippet->type, array(3, 4, 7, 8))) {

		} else {
			$firstParameter = $method->addParameter($property->singular);
			if(!in_array($targetPropertyClass, self::$skipTypeHintIn)) {
				$firstParameter->typeHint = $targetPropertyClass;
			}
		}

		$body = array();
		if($snippet->type == 1) {
			$method->documents[] = sprintf('@param %s', $targetPropertyClass);
			$body[] = sprintf('if(%s$this->%s->contains($%s)) {', ($methodName->prefix == 'add' ? '!' : NULL), $property->name, $firstParameter->name);
			$body[] = sprintf('%s$this->%s->%s($%s);', "\t", $property->name, $snippet->var, $firstParameter->name);
			$body[] = '}';			
			if($snippet->var2 === TRUE) {
				$body[] = sprintf('$%s->%s%s($this);', $parameter, $type, $targetProperty->singularFu);
			} else if($snippet->var2 === FALSE){
				$body[] = sprintf('$%s->set%s($this);', $parameter, $targetProperty->nameFu);
			}
		} else if($snippet->type == 9) {
			$method->documents[] = sprintf('@param %s', $targetPropertyClass);
			$body[] = sprintf('$this->%s->%s($%s);', $property->name, $snippet->var, $firstParameter->name);
			if($snippet->var2 === TRUE) {
				$body[] = sprintf('$%s->%s%s($this);', $parameter, $type, $targetProperty->singularFu);
			} else if($snippet->var2 === FALSE){
				$body[] = sprintf('$%s->unset%s();', $parameter, $targetProperty->nameFu);
			}
		} else if($snippet->type == 2) {
			if($type == 'setInverse') {
				$method->documents[] = sprintf('@warning Bacha inverzna strana!');
			}
			$method->documents[] = sprintf('@param %s', $targetPropertyClass);
			$body[] = sprintf('$this->%s = $%s;', $property->name, $property->name);
			if($type == 'setMapped') {
				$body[] = sprintf('$%s->set%s($this);', $parameter, $targetProperty->nameFu);
			}
		} else if($snippet->type == 3) {
			$method->documents[] = sprintf('@return %s|NULL', $targetPropertyClass);
			$body[] = sprintf('return $this->%s;', $property->name, $property->name);
		} else if($snippet->type == 4) {
			$method->documents[] = sprintf('@todo %s', $property->name);
		} else if($snippet->type == 5) {
			$method->documents[] = sprintf('@param %s', $targetPropertyClass);
			$body[] = sprintf('$this->%s = $%s;', $property->name, $firstParameter->name);
		} else if($snippet->type == 6) {
			$method->documents[] = sprintf('@param %s', $targetPropertyClass);
			$body[] = sprintf('return $%s->add%s($this);', $firstParameter->name, $property->singularFu);
		} else if($snippet->type == 7) {
			$method->documents[] = sprintf('@return \Doctrine\Common\Collections\ArrayCollection|%s[]',
				$targetPropertyClass);
			$body[] = sprintf('return $this->%s;', $property->name, $property->name);
		} else if($snippet->type == 8) {
			$body[] = sprintf('$this->%s = NULL;', $property->name);
			if($type == 'unsetMapped') {
				$body[] = sprintf('$%s->set%s();', $parameter, $targetProperty->nameFu);
			}
		}

		if($snippet->returnThis) {
			$method->documents[] = sprintf('@return %s', '\\'.$property->class);
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
		$body[] = 'parent::__construct();';
		$body[] = '';
		foreach ($collections as $key => $val) {
			$body[] = sprintf('$this->%s = new \Doctrine\Common\Collections\ArrayCollection;', $val->name);
		}
		foreach ($body as $key => $val) {
			$construct->addBody($val);
		}		
	}

}
