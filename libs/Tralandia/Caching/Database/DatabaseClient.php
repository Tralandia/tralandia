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

	/**
	 * @var string
	 */
	private $tagsTable;


	public function __construct(\DibiConnection $connection, $table)
	{
		$this->connection = $connection;
		$this->table = $table;
		$this->tagsTable = $table . '_tag';
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
			'expiration' => $expiration,
		];

		if($row) {
			unset($args['id']);
			$this->connection->query("UPDATE LOW_PRIORITY [{$this->table}] SET ", $args, 'WHERE [id]=%s', $id);
		} else {
			$this->connection->query("INSERT DELAYED INTO [{$this->table}]", $args);

		}

		if(is_array($tags)) {
			$this->connection->query("DELETE FROM [{$this->tagsTable}] WHERE [cache_id] = %s", $id);

			if(count($tags)) {
				$tagValues = [];
				foreach($tags as $tag) {
					$tagValues[] = [
						'cache_id' => $id,
						'tag' => $tag,
					];
				}

				$this->connection->query("INSERT DELAYED INTO [{$this->tagsTable}] %ex", $tagValues);
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
			$expiration = $row->expiration;
			if($expiration !== NULL && $expiration <= time()) {
				break;
			}

			return TRUE;
		} while (FALSE);

		$this->clearByIds([$row->id]);
		return FALSE;
	}


	public function findCacheIdsByTags(array $tags)
	{
		$selection = $this->connection->select('c.id AS cId, count(c.id) AS count')
			->from($this->table . ' AS c')
			->innerJoin($this->tagsTable . ' AS t')->on('t.cache_id = c.id')
			->where('[tag] IN %in', $tags)
			->groupBy('c.id')
			->having('count = %i', count($tags));

		return $selection->fetchPairs('cId', 'cId');
	}


	public function cleanByTags(array $tags)
	{
		if(count($tags)) {
			$ids = $this->findCacheIdsByTags($tags);
			$this->clearByIds($ids);
		}
	}

	public function clearByIds(array $ids)
	{
		$this->connection->delete($this->table)->where('[id] IN %in', $ids)->execute();
		$this->connection->delete($this->tagsTable)->where('[cache_id] IN %in', $ids)->execute();
	}

}
