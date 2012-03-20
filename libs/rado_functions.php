<?php

function q($query, $show = 0) {
	static $link;

	if (!$link) {
		$link=mysql_connect('localhost', 'root', 'root');
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

function addIdPair($oldTable, $oldId, $entity, $newId) {
	q('insert into _idPairs set oldTable = "'.$oldTable.'", oldId = "'.$oldId.'", entity = "'.mysql_real_escape_string($entity).'", newId = "'.$newId.'"');

	return TRUE;
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

function getNewIds($entity, $oldIds) {
	$oldIds = array_filter(array_unique(explode(',', $oldIds)));
	$newIds = array();
	foreach ($oldIds as $key => $value) {
		$newIds[] = (int)qc('select newId from _idPairs where entity = "'.mysql_real_escape_string($entity).'" and oldId = '.$value);
	}
	return $newIds;
}

function fromStamp($stamp) {
	$t = new \Nette\DateTime();
	$t->setTimestamp($stamp);

	return $t;
}