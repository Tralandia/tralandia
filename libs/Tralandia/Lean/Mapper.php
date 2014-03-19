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
		if($classToTable = $this->classToTable($entityClass)) {
			return $classToTable;
		}

		list(,$namespace, $name) = explode('\\', $entityClass);
		if($namespace == $name) {
			return strtolower($name);
		} else {
			return strtolower($namespace) . '_' . strtolower($name);
		}
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
		$matches = array();
		if (preg_match('#([a-z0-9]+)Repository#i', $repositoryClass, $matches)) {
			return strtolower($matches[1]);
		}
		throw new InvalidStateException('Cannot determine table name.');
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

		return array_key_exists($class, $this->classToTable) ? $this->classToTable[$class] : NULL;
	}


}
