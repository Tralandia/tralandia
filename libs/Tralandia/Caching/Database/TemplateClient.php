<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/15/13 8:22 AM
 */

namespace Tralandia\Caching\Database;


use Nette;

class TemplateClient implements IDatabaseClient {


	/**
	 * @var \DibiConnection
	 */
	private $connection;

	/**
	 * @var string
	 */
	private $table;

	/**
	 * @var string
	 */
	private $tagsTable;


	public function __construct(\DibiConnection $connection)
	{
		$this->connection = $connection;
		$this->table = 'template_cache';
	}


	public function save($id, $value, $expiration = NULL, array $tags = NULL)
	{

		$row = $this->find($id);

		if(is_array($value)) {
			$value = Nette\Utils\Json::encode($value);
		} else {
			$value = trim($value);
		}

		$args = [
			'id' => $id,
			'value' => gzcompress($value, 6),
			'valid' => 1,
			'expiration' => $expiration ? Nette\DateTime::from($expiration) : NULL,
		];

		if(is_array($tags)) {
			$args = array_merge($args, $this->tagsToColumns($tags));
		}

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

		if(!$row || !$this->isCacheValid($row)) {
			return NULL;
		}

		$value = gzuncompress($row->value);

		try {
			$temp = Nette\Utils\Json::decode($value, Nette\Utils\Json::FORCE_ARRAY);
			$value = $temp;
		} catch(Nette\Utils\JsonException $e) {

		}

		return $value;
	}

	public function isCacheValid($row)
	{
		do {
			$valid = $row->valid;
			$expiration = $row->expiration;
			if($valid == 0 || ($expiration !== NULL && $expiration <= new Nette\DateTime())) {
				break;
			}

			return TRUE;
		} while (FALSE);

		$this->clearByIds([$row->id]);
		return FALSE;
	}


	public function cleanByTags(array $tags)
	{
		if(count($tags)) {
			$columns = $this->tagsToColumns($tags);
			$this->connection->query("UPDATE [{$this->table}] SET valid = 0 WHERE %or", $columns);

		}
	}

	public function clearByIds(array $ids)
	{
		$this->connection->query("UPDATE [{$this->table}] SET valid = 0 WHERE [id] IN %in", $ids);

	}


	protected function tagsToColumns(array $tags)
	{
		$columns = [];

		foreach($tags as $tag) {
			if(Nette\Utils\Strings::contains($tag, '/')) {
				list($column, $value) = explode('/', $tag, 2);
				$columns['tag' . ucfirst($column)] = $value;
			} else {
				$columns['tagType'] = $tag;
			}
		}

		return $columns;
	}

}
