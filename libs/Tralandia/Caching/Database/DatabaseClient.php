<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/15/13 8:22 AM
 */

namespace Tralandia\Caching\Database;


use Nette;

class DatabaseClient {


	/**
	 * @var \DibiConnection
	 */
	private $connection;

	/**
	 * @var string
	 */
	private $table;


	public function __construct(\DibiConnection $connection, $table)
	{
		$this->connection = $connection;
		$this->table = $table;
	}


	public function save($id, $value, array $tags = NULL)
	{

		$row = $this->find($id);

		if(is_array($tags) && count($tags)) {
			$tags = ',,' . implode(',,', $tags) . ',,';
		}

		if(is_array($value)) {
			$value = Nette\Utils\Json::encode($value);
		} else {
			$value = trim($value);
		}

		$args = [
			'id' => $id,
			'value' => gzcompress($value, 6),
			'tags' => $tags,
		];

		if($row) {
			unset($args['id']);
			$this->connection->query("UPDATE LOW_PRIORITY [{$this->table}] SET ", $args, 'WHERE [id]=%s', $id);
		} else {
			$this->connection->query("INSERT DELAYED INTO [{$this->table}]", $args);
		}
	}


	public function find($id)
	{
		return $this->connection->fetch("SELECT * FROM [{$this->table}] WHERE [id] = %s", $id);
	}


	public function findValue($id)
	{
		$row = $this->find($id);
		$value = $row ? gzuncompress($row->value) : NULL;

		try {
			$temp = Nette\Utils\Json::decode($value, Nette\Utils\Json::FORCE_ARRAY);
			$value = $temp;
		} catch(Nette\Utils\JsonException $e) {

		}

		return $value;
	}


	public function cleanByTag(array $tags)
	{
		$tags = ',,' . implode(',,', $tags) . ',,';
		$this->connection->delete($this->table)->where('tags LIKE %~like~', $tags);
	}
}
