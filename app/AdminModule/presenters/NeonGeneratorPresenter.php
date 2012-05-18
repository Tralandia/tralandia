<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils;
use Nette\Utils\Finder;
use Doctrine\ORM\Mapping as ORM;

class NeonGeneratorPresenter extends EntityGeneratorPresenter {

	private static $destinationFolder = 'configs/presenters/tmp/';

	protected $controlTypes = array(
		'\Extras\Types\Latlong' => 'text',
		'json' => 'json',
		'integer' => 'text',
		'\DateTime' => 'dateTime',
		'string' => 'text',
		'float' => 'float',
		'\Extras\Types\Time' => 'text',
		'\Extras\Types\Address' => 'text',
		'boolean' => 'checkbox',
		'slug' => 'text',
		'decimal' => 'text',
		'\Extras\Types\Price' => 'text',
		'\Extras\Types\Url' => 'text',
		'\Extras\Types\Email' => 'text',
	);

	protected $collectionTypes = array(
		1 => 'phrase', // OneToOne
		2 => 'selectBox', // ManyToOne
		4 => 'checkBoxList', // OneToMany
		8 => 'bricksList', // ManyToMany
	);

	public function actionDefault($id) {

		$lastFolderName = NULL;

		$neon = new Utils\Neon;
		$entityDir = APP_DIR . '/models/Entity/';
		$messageSuccess = array();
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {

			$neonOutput = array();

			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);
			$folderNameTemp = explode('\\', $entityNameTemp, 3);
			array_shift($folderNameTemp);
			$folderName = array_shift($folderNameTemp);

			$anotations = $this->getAnotations($entityNameTemp);

			$grid = array();
			$fields = array();
			$mainEntityReflector = $this->getEntityReflection($entityNameTemp);
			foreach ($mainEntityReflector->getProperties() as $property) {

				$propertyInfo = $this->getPropertyInfo($property);

				$grid[$property->name] = array(
					'label' => $propertyInfo->nameFu,
					'mapper' => 'e.' . $propertyInfo->name,
				);

				if (in_array($property->name, array('oldId', 'id'))) continue;

				$fields[$property->name] = array(
					'\# anotations' => str_replace(array('"','[', ']','{', '}','\\'), '', json_encode($anotations[$property->name])),
					'label' => $propertyInfo->nameFu,
					'control' => $this->getControlType($propertyInfo),
				);
/*
				$comments = array('type', 'isNullable');
				foreach ($propertyInfo as $key => $value) {

					if (!in_array($key, $comments)) continue;
					$fields[$property->name]['\# ' . $key] = $value;

				}
*/
			}

			$title = str_replace(array('Entity\\', '\\'), array('', ' / '), $entityNameTemp);

			$neonOutput['common'] = array(
				'service' => str_replace('Entity\\', 'Service\\', $entityNameTemp),
				'title' => $title,
				'h1' => $title,
				'grid' => array('columns' => $grid),
				'form' => array(
						'fields'=> $fields
					),
			);

			$neonOutput = $neon->encode($neonOutput, $neon::BLOCK);
			$neonOutput = str_replace('\# ', '# ', $neonOutput);

			$file = APP_DIR . '/' . self::$destinationFolder . str_replace(array('Entity\\', '\\'), '', $entityNameTemp) . '.neon';
			fopen($file, 'c');
			file_put_contents($file, preg_replace("/[\n\r]{1}\t{2,4}[\n\r]{1}/","\n", trim($neonOutput)));

		}

	}

	private function getControlType($propertyInfo) {

		$controlType = 'text';

		if (isset($propertyInfo->type)) {
			if (in_array($propertyInfo->type, array_keys($this->controlTypes))) {
				$controlType = $this->controlTypes[$propertyInfo->type];
			}
		}

		if ($propertyInfo->isCollection) {
			$controlType = $this->collectionTypes[$propertyInfo->association];
		}

		return $controlType;

	}

	private function getAnotations($entity) {

		$annotations = array();

		$userEntityReflection = new Reflection\ClassType($entity);
		$properties = $userEntityReflection->getProperties();
		foreach ($properties as $property) {
			$annotations[$property->name] = $property->getAnnotations();
		}

		return $annotations;

	}

}
