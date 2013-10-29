<?php
//ini_set('display_errors', '1'); error_reporting(E_ALL);

if (PHP_SAPI != 'cli') {

	if(array_key_exists('tester', $_GET)) {
		setcookie("tester", (int)$_GET['tester']);
		header("Location: /");
		die();
	}

	if(!array_key_exists('tester', $_COOKIE) || !$_COOKIE['tester']) {
		require __DIR__ . '/landingPage/index.html';
		exit;
	}
}

require __DIR__ . '/bootstrap.php';
