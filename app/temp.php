<?php

error_reporting( E_ALL );
ini_set('display_errors', 1);

include("random-user-agent.php");


echo "start";



function visit($agent, $referer)
{

	//$url = "http://webmagazin.teraz.sk/cestovanie/rental-picker-ubytovanie-lacne/1788-clanok.html";
	$url = "http://127.0.0.1/hint/hint.html";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent;
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_COOKIE, "");
	$content = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	return $httpCode;
}


function random_referer()
{
	$referers = [
		'http://www.facebook.com/',
		'http://www.google.sk/'
		'http://www.google.com/'
		'http://www.twitter.com/'
	];

	return $referers[array_rand($referers)];
}
