<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/15/13 8:06 AM
 */

namespace Tralandia\Caching\Database;


use Nette;

class DatabaseStorage extends Nette\Object implements Nette\Caching\IStorage {


	/**
	 * @var DatabaseClient
	 */
	private $client;


	public function __construct(DatabaseClient $client)
	{
		$this->client = $client;
	}


	/**
	 * Read from cache.
	 * @param  string key
	 * @return mixed|NULL
	 */
	public function read($key)
	{
		return $this->client->findValue($key);
	}

	/**
	 * Prevents item reading and writing. Lock is released by write() or remove().
	 * @param  string key
	 * @return void
	 */
	public function lock($key)
	{

	}

	/**
	 * Writes item into the cache.
	 * @param  string key
	 * @param  mixed  data
	 * @param  array  dependencies
	 * @return void
	 */
	public function write($key, $data, array $dependencies)
	{
		$tags = Nette\Utils\Arrays::get($dependencies, Nette\Caching\Cache::TAGS, NULL);

		$this->client->save($key, $data, $tags);
	}

	/**
	 * Removes item from the cache.
	 * @param  string key
	 * @return void
	 */
	public function remove($key)
	{

	}

	/**
	 * Removes items from the cache by conditions.
	 * @param  array  conditions
	 * @return void
	 */
	public function clean(array $conditions)
	{

	}


}
