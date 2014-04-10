<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 9:50 AM
 */

namespace Tralandia\Lean;


use LeanMapper\Repository;
use Nette;

class BaseRepository extends Repository
{

	protected function initEvents()
	{
		parent::initEvents();
		$this->onBeforePersist[] = function($entity) {
			$entity->updated = $entity->created = new \DateTime();
		};
	}


	public function createNew()
	{
		$className = $this->mapper->getEntityClass($this->getTable());
		return new $className;
	}


	/**
	 * Alias for persist method
	 * @param $entity
	 *
	 * @return mixed
	 */
	public function save($entity)
	{
		return $this->persist($entity);
	}


	/**
	 * @param $id
	 * @param bool $need
	 *
	 * @throws \Exception
	 * @return mixed|null
	 */
	public function find($id, $need = FALSE)
	{
		// first part
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('id = %i', $id)
			->fetch();

		if($need && !$row) {
			throw new EntityNotFoundException("Nenasiel som {$this->getTable()}::{$id}");
		}

		// second part
		return $row ? $this->createEntity($row) : NULL;
	}

	/**
	 * @return array
	 */
	public function findAll()
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->fetchAll()
		);
	}


	/**
	 * @param null $key
	 * @param null $value
	 *
	 * @return array
	 */
	public function findPairs($key = NULL, $value = NULL)
	{
		return $this->connection->select('*')
			->from($this->getTable())
			->fetchPairs($key, $value);
	}


	/**
	 * @param $where
	 *
	 * @return mixed|null
	 */
	public function findOneBy($where)
	{
		$row = $this->connection->select('*')
			->from($this->getTable())
			->where('%and', $where)
			->fetch();

		return $row ? $this->createEntity($row) : NULL;
	}


	/**
	 * @param $where
	 *
	 * @return mixed|null
	 */
	public function findBy($where)
	{
		return $this->createEntities(
			$this->connection->select('*')
				->from($this->getTable())
				->where('%and', $where)
				->fetchAll()
		);
	}

	/**
	 * @param $where
	 *
	 * @return mixed|null
	 */
	public function getCount($where)
	{
		return $this->connection->select('*')
			->from($this->getTable())
			->where('%and', $where)
			->count();
	}


	/**
	 * @param $args
	 * @param $cond
	 */
	public function update($set, $cond)
	{
		$args = func_get_args();
		$fluent = $this->connection->update($this->getTable(), $set);
		if(count($args) > 2) {
			array_shift($args);
			call_user_func_array([$fluent, 'where'], $args);
		} else {
			$fluent->where($cond);
		}

		$fluent->execute();
	}

}


class EntityNotFoundException extends \Exception {}
