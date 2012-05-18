<?php

namespace AdminModule;

use Nette\Reflection;
use Nette\Utils;
use Nette\Utils\Finder;
use Doctrine\ORM\Mapping as ORM;

class NeonGeneratorPresenter extends EntityGeneratorPresenter {

	private static $destinationFolder = 'configs/presenters/tmp/';

	protected $generate = array(
		'Entity\Attraction\Attraction',
		'Entity\Attraction\Type',
		'Entity\Autopilot\Task',
		'Entity\Autopilot\TaskArchived',
		'Entity\Autopilot\Type',
		'Entity\BaseEntity',
		'Entity\BaseEntityDetails',
		'Entity\Company\BankAccount',
		'Entity\Company\Company',
		'Entity\Company\Office',
		'Entity\Contact\Contact',
		'Entity\Contact\Type',
		'Entity\Currency',
		'Entity\Dictionary\Language',
		'Entity\Dictionary\Type',
		'Entity\Domain',
		'Entity\Emailing\Batch',
		'Entity\Emailing\Template',
		'Entity\Expense\Expense',
		'Entity\Expense\Type',
		'Entity\Invoicing\Coupon',
		'Entity\Invoicing\Marketing',
		'Entity\Invoicing\Package',
		'Entity\Invoicing\Service',
		'Entity\Invoicing\ServiceDuration',
		'Entity\Invoicing\ServiceType',
		'Entity\Invoicing\UseType',
		'Entity\Location\Location',
		'Entity\Location\Type',
		'Entity\Medium\Type',
		'Entity\Rental\Amenity',
		'Entity\Rental\AmenityType',
		'Entity\Rental\Rental',
		'Entity\Rental\Type',
		'Entity\Seo\SeoUrl',
		'Entity\Seo\TitleSuffix',
		'Entity\User\Combination',
		'Entity\User\Interaction',
		'Entity\User\InteractionType',
		'Entity\User\Role',
		'Entity\User\User',
	);

	protected $controlTypes = array(
		'boolean' 					=> 'checkbox',
		'json' 						=> 'json',
		'\DateTime' 				=> 'datePicker',
		'float' 					=> 'float',
		'\Extras\Types\Latlong' 	=> 'text',
		'integer' 					=> 'text',
		'string' 					=> 'text',
		'\Extras\Types\Time' 		=> 'text',
		'\Extras\Types\Address'		=> 'text',
		'slug' 						=> 'text',
		'decimal' 					=> 'text',
		'\Extras\Types\Price' 		=> 'text',
		'\Extras\Types\Url' 		=> 'text',
		'\Extras\Types\Email' 		=> 'text',
	);

	protected $collectionTypes = array(
		1 => 'phrase', // OneToOne
		2 => 'selectBox', // ManyToOne
		4 => 'checkBoxList', // OneToMany
		8 => 'bricksList', // ManyToMany
	);

	public function actionDefault($id) {
		
		$this->template->presenters = NULL;
		$this->template->entities = NULL;

		$this->generateNeonFiles();
		
		return false;

		$presenters = array();
		$entities = array();

		$entityDir = APP_DIR . '/models/Entity/';
		foreach (Finder::findFiles('*.php')->from($entityDir) as $key => $file) {

			list($x, $entityNameTemp) = explode('/models/', $key, 2);
			$prensenter = '- Admin:' . str_replace(array('Entity\\', '\\', '.php'), array('', '', ''), $entityNameTemp);
			$entity = '- ' . str_replace('.php', '', $entityNameTemp);

			$presenters[] = $prensenter;
			$entities[] = $entity;

		}

		$presenters = implode('<br/>', $presenters);
		$this->template->presenters = $presenters;

		$entities = implode('<br/>', $entities);
		$this->template->entities = $entities;

	}

	public function generateNeonFiles() {

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
