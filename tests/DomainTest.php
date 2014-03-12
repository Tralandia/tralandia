<?php
$domains = array_unique(explode(',', '.at,.be,.biz,.ch,.co.uk,.com,.com.hr,.com.ua,.cz,.de,.dk,.es,.eu,.fi,.fr,.gr,.hr,.hu,.info,.it,.li,.lt,.lu,.lv,.me,.net,.nl,.no,.org,.pl,.pt,.ro,.ru,.se,.si,.sk,.cn,.bg,.by,.ee,.ie,.is,.rs,.cc,.pm,.re,.yt,.com.mt,.gl,.je,.gg,.co.nz,.in,.co.za,.mx,.qa,.sg,.ae,.tw,.hk,.ph,.co.il,.co.id,.kr,.com.ar,.vn,.com.br,.jp,.co.ke,.my,.do,.com.tr,.al,'));

// Test domains
//$domains = array('.sk', '.hu', '.ske');

function testUrlResponse($url) {
	$urlStatus = NULL;
	$startTime = microtime(true);
	$handle = curl_init($url);
	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

	/* Get the HTML or whatever is linked in $url. */
	$response = curl_exec($handle);

	/* Check for 404 (file not found). */
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if($httpCode >= 200 && $httpCode < 300) {
	    $urlStatus = TRUE;
	} else {
	    $urlStatus = FALSE;
	}

	curl_close($handle);
	return array($urlStatus, round(microtime(true)-$startTime, 3));
}

function myEcho($s, $isTerminal = TRUE) {
	if ($isTerminal) {
		$s = str_replace('<br>', "\n", $s);
		echo(strip_tags($s));
	} else {
		echo($s);
	}
}

$failedDomains = array();

foreach ($domains as $key => $value) {
	$thisDomain = 'www.tralandia'.$value;
	myEcho('Testing <b>'.$thisDomain.'</b> .....');

	$result = testUrlResponse('http://'.$thisDomain, 200, 5);

	if ($result[0] === TRUE) {
		myEcho('<span style="color: darkgreen;">OK</span> '.$result[1].'<br>');
	} else {
		myEcho('<span style="color: red;">ERROR</span> '.$result[1].'<br>');
		$failedDomains[] = $thisDomain;
	}
}

myEcho('<br><br><br><br><br>');

myEcho('Failed domains:<br>');
foreach ($failedDomains as $key => $value) {
	myEcho($value.'<br>');
}





