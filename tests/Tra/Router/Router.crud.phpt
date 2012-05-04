<?php
use Extras;
require __DIR__ . '/../bootstrap.php';

$samples = array(
	'http://www.tra.sk/chaty/nitra-okres/prazdninovy-pobyt?lfPeople=6&lfFood=6' => 'http://www.tra.sk/nitra-okres/chaty/prazdninovy-pobyt?lfPeople=6&lfFood=6'
);

$router = new \Extras\Route($container->routerCache, array(
								'presenter' => 'David',
								'action' => 'default',
								'country' => 'SK',
							));

foreach ($samples as $key => $value) {
	$scriptUrl = new Nette\Http\UrlScript($key);
	$httpRequest = new Nette\Http\Request($scriptUrl, $scriptUrl->getQuery(), $post = NULL, $files = NULL, $cookies = NULL, $headers = NULL, $method = 'GET', $remoteAddress = NULL, $remoteHost = NULL);
	print_r($router->match($httpRequest));
}