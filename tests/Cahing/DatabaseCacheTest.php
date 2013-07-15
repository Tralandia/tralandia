<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;


/**
 * @backupGlobals disabled
 */
class DatabaseCacheTest extends \Tests\TestCase
{

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $cache;

	public function setUp()
	{
		$databaseStorage = $this->getContext()->databaseCacheStorage;
		$this->cache = new Nette\Caching\Cache($databaseStorage);
	}

	public function testBase()
	{
		$key = 'test';
		$value = 'value';
		$this->cache->save($key, $value);

		$this->assertEquals($value, $this->cache->load($key));
	}

}
