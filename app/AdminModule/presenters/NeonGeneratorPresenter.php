<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils;
use Nette\Utils\Finder;
use Doctrine\ORM\Mapping as ORM;

class NeonGeneratorPresenter extends EntityGeneratorPresenter {

	private static $destinationFolder = 'configs/presenters/tmp/';

	protected $skipEntities = array(
		'Entity\Dictionary\Phrase',
		'Entity\Dictionary\Translation',
		'Entity\Invoice\Invoice',
		'Entity\Invoice\Item',
		'Entity\Location\Traveling',
		'Entity\Log\Change',
		'Entity\Log\ChangeType',
		'Entity\Log\System',
		'Entity\Medium\Medium',
		'Entity\Rental\Fulltext',
		'Entity\Routing\PathSegment',
		'Entity\Routing\PathSegmentOld',
		'Entity\Ticket\Message',
		'Entity\Ticket\Ticket',
	);

	protected $controlTypes = array(
		'\DateTime' 				=> 'datePicker',
		'boolean' 					=> 'checkbox',
		'float' 					=> 'numeric',
		'integer' 					=> 'numeric',
		'decimal' 					=> 'numeric',
		'\Extras\Types\Price' 		=> 'numeric',
		'json' 						=> 'json',
		'\Extras\Types\Latlong' 	=> 'text',
		'string' 					=> 'text',
		'\Extras\Types\Time' 		=> 'text',
		'\Extras\Types\Address'		=> 'text',
		'slug' 						=> 'text',
		'\Extras\Types\Url' 		=> 'text',
		'\Extras\Types\Email' 		=> 'text',
	);

	protected $collectionTypes = array(
		1 => 'phrase', // OneToOne
		2 => 'select', // ManyToOne
		4 => 'multiSelect', // OneToMany
		8 => 'bricksList', // ManyToMany
	);

	public function actionDefault($id) {
		
		$this->template->presenters = NULL;
		$this->template->entities = NULL;
		$this->template->generatedFiles = NULL;

		$this->generateJsonStructoresFile();

/*
		$presenters = array();
		$entities = array();

		$entityDir = APP_DIR . '/models/Entity/';
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {

			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			if (in_array(str_replace('.php', '', $entityNameTemp), $this->skipEntities)) continue;

			$prensenter = '- Admin:' . str_replace(array('Entity\\', '\\', '.php'), array('', '', ''), $entityNameTemp);
			$entity = '- ' . str_replace('.php', '', $entityNameTemp);

			$presenters[] = $prensenter;
			$entities[] = $entity;

		}

		$presenters = implode('<br/>', $presenters);
		$this->template->presenters = $presenters;

		$entities = implode('<br/>', $entities);
		$this->template->entities = $entities;

		$this->template->generatedFiles = implode('<br/>', $this->generateNeonFiles());
*/

	}

	private function generateJsonStructoresFile() {

		$jsonStructures = array();
		$entityDir = APP_DIR . '/models/Entity/';
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {

			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);

			$mainEntityReflector = $this->getEntityReflection($entityNameTemp);
			foreach ($mainEntityReflector->getProperties() as $property) {

				$propertyInfo = $this->getPropertyInfo($property);
				$controlType = $this->getControlType($propertyInfo);

				if ($controlType != 'json') continue;

				$jsonStructures[str_replace('Entity\\', '', $entityNameTemp)][$propertyInfo->name] = array(
					'item1'=>NULL,
					'item2'=>NULL
				);

			}

		}

		$neon = new Utils\Neon;
		$neonOutput = $neon->encode($jsonStructures, $neon::BLOCK);

		$file = APP_DIR . '/' . self::$destinationFolder . 'jsonStructures.neon';
		fopen($file, 'c');
		file_put_contents($file, preg_replace("/[\n\r]{1}\t{1,4}[\n\r]{1}/","\n", trim($neonOutput)));	

	}

	/**
	 * Generates .neon files by entities
	 * @return array of generated neon files
	 */
	public function generateNeonFiles() {

		$lastFolderName = NULL;

		$neon = new Utils\Neon;
		$entityDir = APP_DIR . '/models/Entity/';
		$messageSuccess = array();
		$generatedFiles = array();
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $value) {

			$neonOutput = array();
/*
			$neonPresenter = array(
				'list' => array(
					'guest' => 'allow',
					'visitor' => 'deny',
					'potentialowner' => 'deny',
					'owner' => 'deny',
					'translator' => 'deny',
					'assistant' => 'deny',
					'vendor' => 'deny',
					'manager' => 'deny',
					'admin' => 'deny',
				),
				'edit' => array(
					'guest' => 'allow',
					'visitor' => 'deny',
					'potentialowner' => 'deny',
					'owner' => 'deny',
					'translator' => 'deny',
					'assistant' => 'deny',
					'vendor' => 'deny',
					'manager' => 'deny',
					'admin' => 'deny',
				),
				'add' => array(
					'guest' => 'allow',
					'visitor' => 'deny',
					'potentialowner' => 'deny',
					'owner' => 'deny',
					'translator' => 'deny',
					'assistant' => 'deny',
					'vendor' => 'deny',
					'manager' => 'deny',
					'admin' => 'deny',
				)
			);
*/
			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$entityNameTemp = str_replace(array('/', '.php'), array('\\', ''), $entityNameTemp);

			$neonFile = str_replace(array('Entity\\', '\\'), '', $entityNameTemp) . '.neon';
			// $neonPresenterFile = 'Admin-' . str_replace(array('Entity\\', '\\'), '', $entityNameTemp) . '.neon';

			// skip if neon is exists in parent folder
			if (file_exists(APP_DIR . '/' . self::$destinationFolder . '../' . $neonFile)) continue;

			// SKIP ENTITIES
			if (in_array($entityNameTemp, $this->skipEntities)) continue;

			$folderNameTemp = explode('\\', $entityNameTemp, 3);
			array_shift($folderNameTemp);
			$folderName = array_shift($folderNameTemp);

			$anotations = $this->getAnotations($entityNameTemp);

			$grid = array();
			$fields = array();
			$mainEntityReflector = $this->getEntityReflection($entityNameTemp);
			foreach ($mainEntityReflector->getProperties() as $property) {

				if ($property->name == 'id') continue;

				$propertyInfo = $this->getPropertyInfo($property);
				$controlType = $this->getControlType($propertyInfo);

				$grid[$property->name]['label'] = $propertyInfo->nameFu;
				$grid[$property->name]['mapper'] = 'e.' . $propertyInfo->name;
				if ($controlType == 'numeric') {
					$controlType = 'text';
					$grid[$property->name]['cellStyle'] = 'text-align:right;';
				}

				if (in_array($property->name, array('oldId', 'id'))) continue;

				$fields[$property->name]['\# anotations'] = str_replace(array('"','[', ']','{', '}','\\'), '', json_encode($anotations[$property->name]));
				$fields[$property->name]['label'] = $propertyInfo->nameFu;
				$fields[$property->name]['control'] = $controlType;

			}

			// add ID column at the start
			$grid = array_merge(array(
				'id'=>array(
					'label' => 'ID',
					'mapper' => 'e.id'
					)
				)
			, $grid);


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

			$neonPresenter = $neon->encode($neonPresenter, $neon::BLOCK);

			$file = APP_DIR . '/' . self::$destinationFolder . $neonFile;
			fopen($file, 'c');
			file_put_contents($file, preg_replace("/[\n\r]{1}\t{2,4}[\n\r]{1}/","\n", trim($neonOutput)));

			// $file2 = APP_DIR . '/' . self::$destinationFolder . 'presenters/' . $neonPresenterFile;
			// fopen($file2, 'c');
			// file_put_contents($file2, preg_replace("/[\n\r]{1}\t{2,4}[\n\r]{1}/","\n", trim($neonPresenter)));
			
			$generatedFiles[] = $neonFile;
			
		}

		return $generatedFiles;

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
