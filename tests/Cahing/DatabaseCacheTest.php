<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;
use Nette\Caching\Cache;


/**
 * @backupGlobals disabled
 */
class DatabaseCacheTest extends \Tests\TestCase
{

	/**
	 * @var \Nette\Caching\Cache
	 */
	private $cache;

	/**
	 * @var string
	 */
	private $key;

	public function setUp()
	{
		$databaseStorage = $this->getContext()->templateCacheStorage;
		$this->cache = new Nette\Caching\Cache($databaseStorage);
		$this->key = 'test1';
	}

	public function testSave()
	{
		$value = 'value';
		$this->cache->save($this->key, $value, [
			Cache::TAGS => ['testTag', 'tag2'],
			Cache::EXPIRATION => '5'
		]);

		$this->assertEquals($value, $this->cache->load($this->key));
	}

	public function testClean()
	{
		$this->cache->clean([
			Cache::TAGS => ['tag24'],
		]);

		$this->assertEquals(NULL, $this->cache->load($this->key));
	}

	public function testExpiration()
	{
		$value = 'value';
		$this->cache->save($this->key, $value, [
			Cache::EXPIRATION => 'tomorrow'
		]);

		$this->assertEquals($value, $this->cache->load($this->key));
		sleep(2);
		$this->assertEquals(NULL, $this->cache->load($this->key));
	}

}
