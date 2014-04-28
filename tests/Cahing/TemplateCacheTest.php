<?php
namespace Tests\DataIntegrity;

use Entity\BaseEntity;
use Nette, Extras;
use Nette\Caching\Cache;


/**
 * @backupGlobals disabled
 */
class TemplateCacheTest extends \Tests\TestCase
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


	public function testSaveAndClean()
	{
		$value = 'value';
		$this->cache->save($this->key, $value, [
			Cache::TAGS => ['rental/21475', 'primaryLocation/sk', 'language/sk', 'footer']
		]);

		$key2 = Nette\Utils\Strings::random();
		$this->cache->save($key2, $value, [
			Cache::TAGS => ['header']
		]);

		$this->assertEquals($value, $this->cache->load($this->key));
		$this->assertEquals($value, $this->cache->load($key2));

		$this->cache->clean([
			Cache::TAGS => ['rental/21475', 'header'],
		]);

		$this->assertEquals(NULL, $this->cache->load($this->key));
		$this->assertEquals(NULL, $this->cache->load($key2));
	}

	public function testExpiration()
	{
		$value = 'value';
		$this->cache->save($this->key, $value, [
			Cache::EXPIRATION => '1'
		]);

		$this->assertEquals($value, $this->cache->load($this->key));
		sleep(1);
		$this->assertEquals(NULL, $this->cache->load($this->key));
	}

}
