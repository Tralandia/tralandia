<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 9/11/13 1:43 PM
 */

namespace Tralandia\Lean;


use LeanMapper\DefaultMapper;
use LeanMapper\Exception\InvalidStateException;
use LeanMapper\Row;
use Nette;

class Mapper extends DefaultMapper {

	/** @var string */
	protected $defaultEntityNamespace = 'Tralandia';

	protected $tableToClass = [
		'rental_pricelist' => '\Tralandia\Rental\PriceListFile',
		'currency' => '\Tralandia\Currency',
		'user_sitereview' => '\Tralandia\SiteReview\SiteReview',
		'page' => '\Tralandia\Routing\Page',
	];

	protected $classToTable;


	/**
	 * @param string $table
	 * @param Row $row
	 *
	 * @return string
	 */
	public function getEntityClass($table, Row $row = null)
	{
		if($tableToClass = $this->tableToClass($table)) {
			return $tableToClass;
		}

		if(Nette\Utils\Strings::contains($table, '_')) {
			list($namespace, $name) = explode('_', $table);
		} else {
			$namespace = $name = $table;
		}
		return $this->defaultEntityNamespace . '\\' . ucfirst($namespace) . '\\' . ucfirst($name);
	}

	/**
	 * @inheritdoc
	 */
	public function getTable($entityClass)
	{
		return $this->getTableFromClass($entityClass);
	}

	/**
	 * @inheritdoc
	 */
	public function getRelationshipTable($sourceTable, $targetTable)
	{
		$targetTable = $this->removeTableNamespace($targetTable);
		return $this->relationshipTableGlue . $targetTable . $this->relationshipTableGlue . $sourceTable;
	}


	/**
	 * @inheritdoc
	 */
	public function getTableByRepositoryClass($repositoryClass)
	{
		$repositoryClass = str_replace('Repository', '', $repositoryClass);
		return $this->getTable($repositoryClass);
	}

	/**
	 * @inheritdoc
	 */
	public function getRelationshipColumn($sourceTable, $targetTable)
	{
		$column = $this->removeTableNamespace($targetTable);
		return $column . '_' . $this->getPrimaryKey($targetTable);
	}


	/**
	 * @param $class
	 *
	 * @return null|string
	 */
	protected function getTableFromClass($class)
	{
		if($classToTable = $this->classToTable($class)) {
			return $classToTable;
		}

		$name = NULL;
		@list(,$namespace, $name) = explode('\\', $class);
		if($namespace == $name || !$name) {
			return strtolower($namespace);
		} else {
			return strtolower($namespace) . '_' . strtolower($name);
		}
	}

	/**
	 * @param $table
	 *
	 * @return string
	 */
	protected function removeTableNamespace($table)
	{
		if(Nette\Utils\Strings::contains($table, '_')) {
			list($namespace, $name) = explode('_', $table);
			return $name;
		} else {
			return $table;
		}
	}


	/**
	 * @param $table
	 *
	 * @return null|string
	 */
	protected function tableToClass($table)
	{
		return array_key_exists($table, $this->tableToClass) ? $this->tableToClass[$table] : NULL;
	}


	/**
	 * @param $class
	 *
	 * @return null|string
	 */
	protected function classToTable($class)
	{
		if(!$this->classToTable) {
			$this->classToTable = array_flip($this->tableToClass);
		}

		if(!Nette\Utils\Strings::startsWith($class, '\\')) {
			$class = '\\' . $class;
		}

		return array_key_exists($class, $this->classToTable) ? $this->classToTable[$class] : NULL;
	}


}
