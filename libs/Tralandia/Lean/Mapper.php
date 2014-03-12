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


	/**
	 * @param string $table
	 * @param Row $row
	 *
	 * @return string
	 */
	public function getEntityClass($table, Row $row = null)
	{
		if($table == 'log') {
			return $this->defaultEntityNamespace . '\\Diagnostics\\' . ucfirst($table);
		} else {
			return $this->defaultEntityNamespace . '\\' . ucfirst($table) . '\\' . ucfirst($table);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getTable($entityClass)
	{
		return lcfirst($this->trimNamespace($entityClass));
	}

	public function getTableByRepositoryClass($repositoryClass)
	{
		$matches = array();
		if (preg_match('#([a-z0-9]+)Dao$#i', $repositoryClass, $matches)) {
			return strtolower($matches[1]);
		}
		throw new InvalidStateException('Cannot determine table name.');
	}


}
