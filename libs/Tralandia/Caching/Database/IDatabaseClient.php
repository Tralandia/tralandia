<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 25/04/14 15:29
 */

namespace Tralandia\Caching\Database;


use Nette;

interface IDatabaseClient
{

	public function findValue($key);
	public function save($id, $value, $expiration = NULL, array $tags = NULL);
	public function clearByIds(array $keys);
	public function cleanByTags(array $tags);

}
