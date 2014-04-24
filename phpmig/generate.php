<?php
/**
 * Jednoduchý generátor migrací. Vytvoří ze šablonek PHP soubor migrace a volitelně i SQL soubory.
 *
 * Použití:
 *
 * 		php generate.php jmeno_me_migrace [-u -d -n]
 *
 * Výchozí podoba vytvoří SQL soubory a připraví migraci jak pro UP, tak pro DOWN:
 *
 *		php generate.php jmeno_me_migrace -u -d
 *
 * Přepínače:
 * 		-u 		Chci UP migraci
 * 		-d 		Chci DOWN migraci
 * 		-n		Nechci generovat žádné SQL soubory
 *
 * Pokud je u příkazu zadána jedna z možností -u a -d, bere se to tak, že tu druhou nechci.
 *
 */

date_default_timezone_set('Europe/Prague');

define('MIGRATIONS_DIR',  __DIR__ . '/migrations');
define('TEMPLATES_DIR',  __DIR__ . '/templates');

iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');

function msg($msg)
{
	echo $msg."\n";
}

function snakeToCamel($val)
{
	return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $val)));
}

if (!isset($argv[1])) {
	msg('Prvni argument je nutny zadat jako jmeno migrace (ve tvaru snake_case).');
	exit;
}

// aliasy přepínačů
$noSqlKeywords = ['-n', '-no', '-no-sql', '-nosql'];
$upKeywords = ['-u', '-up'];
$downKeywords = ['-d', '-down'];

// jméno třídy migrace
$migrationName = snakeToCamel(trim($argv[1]));
// základ jména souborů
$name = date('YmdHis') . '_' . trim(str_replace('-', '_', $argv[1]));
// chceme SQL?
$addSql = TRUE;
// UP a DOWN?
$upDown = [];

for ($i = 1; $i < $_SERVER['argc']; $i++) {
	$argument  = $_SERVER['argv'][$i];

	if (in_array($argument, $noSqlKeywords)) {
		$addSql = FALSE;
	} elseif (in_array($argument, $upKeywords)) {
		$upDown[] = 'up';
	} elseif (in_array($argument, $downKeywords)) {
		$upDown[] = 'down';
	}
}

// výchozí nastavení - vytváříme UP i DOWN
if (empty($upDown)) {
	$upDown = ['up', 'down'];
}

$saveToDir = MIGRATIONS_DIR . '/' . $name;
if(is_dir($saveToDir)) {
	msg('Priecinok ' . $saveToDir . ' už existuje.');
	exit;
}
@mkdir($saveToDir, 0777, TRUE);

if ($addSql) {
	foreach ($upDown as $suffix) {
		$sqlFilename = $saveToDir . '/' . $name . '_' . $suffix . '.sql';
		if (file_exists($sqlFilename)) {
			msg('SQL soubor, který chcete vytvořit už existuje: ' . $name . '_' . $suffix . '.sql');
		}
		file_put_contents($sqlFilename, "-- " . $migrationName . ' migration ' . strtoupper($suffix) . ' file');
		msg('+f ' . $sqlFilename);
	}
}

$templateName = 'template_' . implode('_', $upDown) . ($addSql ? '' : '_nosql') . '.php';
$templateText = file_get_contents(TEMPLATES_DIR . '/' . $templateName);
$templateText = str_replace('MigrationName', $migrationName, $templateText);

$migrationFilename = $saveToDir . '/' . $name . '.php';

if (file_exists($migrationFilename)) {
	msg('PHP soubor pro migraci, který chcete vytvořit už existuje: ' . $name . '.sql');
}

file_put_contents($migrationFilename, $templateText);
msg('+f ' . $migrationFilename);
