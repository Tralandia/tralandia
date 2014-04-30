<?php
namespace Tests\Entity;

use Nette, Extras;


/**
 * Testujem ci sa pri vytvoreni novej entity spravne vytvoria aj preklady (ak sa maju)\
 *
 * @backupGlobals disabled
 */
class PingTest extends \Tests\TestCase
{

	public $domainRepository;

	protected function setUp() {
		$this->domainRepository = $this->getEm()->getRepository(DOMAIN_ENTITY);
	}


	public function testCurl() {
		$actual = [];

		/** @var $domain \Entity\Domain */
		foreach($this->domainRepository->findAll() as $domain) {
			$host = $domain->getDomain();
			$url = 'www.'.$host;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_NOBODY, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_exec($ch);
			$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if($responseCode != 200) {
				$actual[$url] = $responseCode;
			}
		}
		$this->assertEquals([], $actual);
	}

	public function testHost() {
		$actual = [];

		/** @var $domain \Entity\Domain */
		foreach($this->domainRepository->findAll() as $domain) {
			$url = $domain->getDomain();
			$url = 'www.'.$url;
			$host = gethostbyname($url);

			if($host != '109.74.151.59') {
				$actual[$url] = $host;
			}
		}
		$this->assertEquals([], $actual);
	}

}
