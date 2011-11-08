<?php

namespace DataGrid\DataSources\Database;

use DataGrid\DataSources\IDataSource,
	DataGrid\DataSources,
	dibi;

/**
 * Dibi data source based data source
 * @author Branislav Vaculčiak
 */
class Selection extends DataSources\DataSource
{
	/**
	 * @var Nette\Database\Table\Selection instance
	 */
	private $ds;

	/**
	 * @var array Fetched data
	 */
	protected $data;

	/**
	 * Store given dibi data source instance
	 * @param \DibiDataSource
	 * @return IDataSource
	 */
	public function __construct(\Nette\Database\Table\Selection $ds) {
		$this->ds = $ds;
	}

	/**
	 * Get list of columns available in datasource
	 * @return array
	 */
	public function getColumns() {
		throw new \NotSupportedException;
	}

	/**
	 * Does datasource have column of given name?
	 * @return boolean
	 */
	public function hasColumn($name) {
		throw new \NotSupportedException;
	}

	/**
	 * Return distinct values for a selectbox filter
	 * @param string Column name
	 * @return array
	 */
	public function getFilterItems($column) {
		throw new \NotImplementedException;
	}

	/**
	 * Add filtering onto specified column
	 * @param string column name
	 * @param string filter
	 * @param string|array operation mode
	 * @param string chain type (if third argument is array)
	 * @throws \InvalidArgumentException
	 * @return IDataSource
	 */
	public function filter($column, $operation = IDataSource::EQUAL, $value = NULL, $chainType = NULL) {
		if (is_array($operation)) {
			if ($chainType !== self::CHAIN_AND && $chainType !== self::CHAIN_OR) {
				throw new \InvalidArgumentException('Invalid chain operation type.');
			}
			$conds = array();
			foreach ($operation as $t) {
				$this->validateFilterOperation($t);
				if ($t === self::IS_NULL || $t === self::IS_NOT_NULL) {
					$conds[] = array('%n', $column, $t);
				} else {
					$modifier = is_double($value) ? dibi::FLOAT : dibi::TEXT;
					if ($operation === self::LIKE || $operation === self::NOT_LIKE)
						$value = DataSources\Utils\WildcardHelper::formatLikeStatementWildcards($value);

					$conds[] = array('%n', $column, $t, '%' . $modifier, $value);
				}
			}

			if ($chainType === self::CHAIN_AND) {
				foreach ($conds as $cond) {
					$this->ds->where($cond);
				}
			} elseif ($chainType === self::CHAIN_OR) {
				$this->ds->where('%or', $conds);
			}
		} else {
			$this->validateFilterOperation($operation);

			if ($operation === self::IS_NULL || $operation === self::IS_NOT_NULL) {
				$this->ds->where('%n', $column, $operation);
			} else {
				$modifier = is_double($value) ? dibi::FLOAT : dibi::TEXT;
				if ($operation === self::LIKE || $operation === self::NOT_LIKE)
					$value = DataSources\Utils\WildcardHelper::formatLikeStatementWildcards($value);

				$this->ds->where('%n', $column, $operation, '%' . $modifier, $value);
			}
		}
	}

	/**
	 * Adds ordering to specified column
	 * @param string column name
	 * @param string one of ordering types
	 * @throws \InvalidArgumentException
	 * @return IDataSource
	 */
	public function sort($column, $order = IDataSource::ASCENDING) {
		$this->ds->order($column . ($order === self::ASCENDING ? ' ASC' : ' DESC'));

		return $this;
	}

	/**
	 * Reduce the result starting from $start to have $count rows
	 * @param int the number of results to obtain
	 * @param int the offset
	 * @throws \OutOfRangeException
	 * @return IDataSource
	 */
	public function reduce($count, $start = 0) {
		if ($count != NULL && $count <= 0) { //intentionally !=
			throw new \OutOfRangeException;
		}

		if ($start != NULL && ($start < 0 || $start > count($this)))
			throw new \OutOfRangeException;

		$this->ds->limit($count == NULL ? NULL : $count, $start == NULL ? NULL : $start);
		return $this;
	}

	/**
	 * Get iterator over data source items
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		return $this->fetch();
	}

	/**
	 * Fetches and returns the result data.
	 * @return array
	 */
	public function fetch() {
		$this->data = $this->ds;
		return $this->ds;
	}

	/**
	 * Count items in data source
	 * @return integer
	 */
	public function count() {
		return (int) $this->ds->count();
	}

	/**
	 * Clone dibi datasource instance
	 * @return void
	 */
	public function __clone() {
		$this->ds = clone $this->ds;
	}
}