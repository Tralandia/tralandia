<?php

function q($query, $show = 0) {
	static $link;

	if (!$link) {
		$link=mysql_connect('localhost', 'root', 'root');
		q("SET character_set_client = binary;"); q("SET character_set_connection = binary;"); q("SET character_set_results = binary;"); q("SET character_set_database = binary;"); q("SET character_set_server = binary;");
	}
	mysql_select_db('tralandia_old', $link);

	if ($show == 1) debug($query);
	if ($r =@mysql_query($query, $link)) {
		return $r;
	} else {
		debug($query." ---> mySQL Error: ".mysql_error($link));
		return FALSE;
	}
}

function qNew($query, $show = 0) {
	static $link1;

	if (!$link1) {
		$link1=mysql_connect('localhost', 'root', 'root');
	}
	mysql_select_db('tralandia', $link1);

	if ($show == 1) debug($query);
	if ($r = @mysql_query($query, $link1)) {
		return $r;
	} else {
		debug($query." ---> mySQL Error: ".mysql_error($link1));
		return FALSE;
	}
}

function qf($query, $show = 0, $associative=0) {
	$r=q($query, $show);
	return mysql_fetch_array($r, (($associative==0)?(MYSQL_BOTH):(MYSQL_ASSOC)));
}

function qc($query, $show = 0) {
	$r= mysql_fetch_array(q($query, $show));
	return $r[0];
}

function explode2Levels($delimiterLevel1, $delimiterLevel2, $a) {
	$temp = explode($delimiterLevel1, $a);
	$temp2 = array();
	foreach ($temp as $key => $val) {
		$val = explode($delimiterLevel2, $val);
		$temp2[$val[0]] = @$val[1];
	}
	return $temp2;
}

function getByOldId($entityName, $oldId) {
	$tableName = str_replace('\\', '_', $entityName);
	$tableName = trim($tableName, '_');
	$tableName = strtolower($tableName);

	$r = qNew('select id from '.$tableName.' where oldId = '.$oldId);
	$id = mysql_fetch_array($r);
	$id = $id[0];
	return $id;
}

function getNewIds($entityName, $oldIds) {
	$tableName = str_replace('\\', '_', $entityName);
	$tableName = trim($tableName, '_');
	$tableName = strtolower($tableName);

	$oldIds = array_filter(array_unique(explode(',', $oldIds)));
	$newIds = array();
	foreach ($oldIds as $key => $value) {
		$r = qNew('select id from '.$tableName.' where oldId = '.$value);
		$id = mysql_fetch_array($r);
		$newIds[] = $id[0];
	}
	return $newIds;
}

function getNewIdsByOld($entityName) {
	$tableName = str_replace('\\', '_', $entityName);
	$tableName = trim($tableName, '_');
	$tableName = strtolower($tableName);

	$r = qNew('select id, oldId from '.$tableName);
	$ids = array();
	while ($x = mysql_fetch_array($r)) {
		$ids[$x['oldId']] = $x['id'];
	}
	return $ids;
}

function fromStamp($stamp) {
	$t = new \Nette\DateTime();
	$t->setTimestamp($stamp);

	return $t;
}

function getLangByIso($iso) {
	$id = qNew('select id from dictionary_language where iso = "'.$iso.'"');
	$id = mysql_fetch_array($id);
	$id = $id[0];

	return \Services\Dictionary\LanguageService::get($id);
}

function getCurrencyByIso($iso) {
	$id = qNew('select id from currency where iso = "'.$iso.'"');
	$id = mysql_fetch_array($id);
	$id = $id[0];

	return \Services\CurrencyService::get($id);
}

function getSupportedLanguages() {
	$id = qNew('select group_concat(id separator ",") from dictionary_language where supported = 1');
	$id = mysql_fetch_array($id);
	return explode(',', $id[0]);
}



