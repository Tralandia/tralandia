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

	protected $mapper = [
		'rental' => '\Tralandia\Rental\Rental',
		'rental_image' => '\Tralandia\Rental\Image',
	];


	/**
	 * @param string $table
	 * @param Row $row
	 *
	 * @return string
	 */
	public function getEntityClass($table, Row $row = null)
	{
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
		list(,$namespace, $name) = explode('\\', $entityClass);
		if($namespace == $name) {
			return lcfirst($name);
		} else {
			return lcfirst($namespace . '_' . $name);
		}
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
