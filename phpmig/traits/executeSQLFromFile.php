<?php


use Nette\Utils\Finder;

/**
 * Umožní třídě načíst soubor z SQL
 *
 * @author pH
 */
trait ExecuteSqlFromFile
{
	/**
	 * Provede všechny SQL dotazy, které jsou:
	 * - ve složce /migrations/sql
	 * - mají příponu .sql
	 * - a obsahují daný affix
	 */
	private function executeSqlFromFile($affix = NULL)
	{
		foreach (Finder::findFiles($this->version . '*' . $affix . '.sql')->in(__DIR__ . '/../migrations/sql/' ) as $file) {
			$this->executeOneSqlFile($file);
		}

	}

	private function executeOneSqlFile($filename)
	{
		echo '    Loading SQL file: ' . $filename->getBaseName('.sql');
		$this->getContainer()['dibi']->loadFile($filename);
		echo " ....done \n";
	}



}
