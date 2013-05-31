<?php

function q($query, $show = 0) {
	static $link;

	//d($_SERVER['HTTP_HOST']);
	if (!$link) {
		if (strpos($_SERVER['HTTP_HOST'], 'tralandia.org') !== FALSE) {
			$link = mysql_connect('127.0.0.1', 'tralandia_old', '0987267789372');		
		} else {
			$link = mysql_connect('127.0.0.1', 'root', 'root');		
		}
		q("SET NAMES utf8");
		q("SET character_set_client = utf8;");
		q("SET character_set_connection = utf8;");
		q("SET character_set_results = utf8;"); 
		q("SET character_set_database = utf8;");
		q("SET character_set_server = utf8;");
	}
	mysql_select_db('tralandia_old', $link);

	if ($show == 1) d($query);
	if ($r =@mysql_query($query, $link)) {
		return $r;
	} else {
		d($query." ---> mySQL Error: ".mysql_error($link));
		return FALSE;
	}
}

function qNew($query, $show = 0) {
	static $link1;

	if (!$link1) {
		if (strpos($_SERVER['HTTP_HOST'], 'tralandia.org') !== FALSE) {
			$link1 = mysql_connect('127.0.0.1', 'tralandia', '986269962525');
		} else {
			$link1 = mysql_connect('127.0.0.1', 'root', 'root');		
		}
		qNew("SET NAMES utf8");
		qNew("SET character_set_client = utf8;");
		qNew("SET character_set_connection = utf8;");
		qNew("SET character_set_results = utf8;"); 
		qNew("SET character_set_database = utf8;");
		qNew("SET character_set_server = utf8;");
	}
	mysql_select_db('tralandia', $link1);

	if ($show == 1) d($query);
	if ($r = @mysql_query($query, $link1)) {
		return $r;
	} else {
		d($query." ---> mySQL Error: ".mysql_error($link1));
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
	$tableName = getTableName($entityName);

	$r = qNew('select id from '.$tableName.' where oldId = '.$oldId);
	$id = mysql_fetch_array($r);
	$id = $id[0];
	return $id;
}

function getNewIds($entityName, $oldIds) {
	$tableName = getTableName($entityName);

	$oldIds = array_filter(array_unique(explode(',', $oldIds)));
	$newIds = array();
	foreach ($oldIds as $key => $value) {
		$r = qNew('select id from '.$tableName.' where oldId = '.$value);
		$id = mysql_fetch_array($r);
		$newIds[] = $id[0];
	}
	return $newIds;
}

function getNewIdsByOld($entityName, $extraWhere = NULL) {
	$tableName = getTableName($entityName);
	$r = qNew('select id, oldId from '.$tableName.($extraWhere ? ' where '.$extraWhere : ''));
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


function getSupportedLanguages() {
	$id = qNew('select group_concat(id separator ",") from language where supported = 1');
	$id = mysql_fetch_array($id);
	return array_filter(explode(',', $id[0]));
}

function getAllLanguages() {
	$id = qNew('select group_concat(id separator ",") from language');
	$id = mysql_fetch_array($id);
	return array_filter(explode(',', $id[0]));
}

function getTableName($namespace) {
	$tableName = array_filter(array_unique(explode('\\', $namespace)));
	$tableName = strtolower(implode('_', $tableName));
	return $tableName;
}

