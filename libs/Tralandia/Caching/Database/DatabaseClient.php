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

		$args = [
			'id' => $id,
			'value' => gzcompress(trim($value), 6),
			'tags' => $tags,
		];

		if($row) {
			unset($args['id']);
			$this->connection->query("UPDATE [{$this->table}] SET ", $args, 'WHERE [id]=%i', $id);
		} else {
			$row = $this->connection->query("SELECT * FROM [{$this->table}] WHERE [tags] = %s", $tags);
			$this->connection->query("INSERT INTO [{$this->table}]", $args);
			if($row) {
				Nette\Diagnostics\Debugger::log('Pridal som uz existujuci tag: '.$tags);
			}
		}
	}


	public function find($id)
	{
		return $this->connection->fetch("SELECT * FROM [{$this->table}] WHERE [id] = %s", $id);
	}


	public function findValue($id)
	{
		$row = $this->find($id);
		return $row ? gzuncompress($row->value) : NULL;
	}

}
