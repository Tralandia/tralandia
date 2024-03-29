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
		$classFileName = \Nette\Reflection\ClassType::from(__CLASS__)->getFileName();
		$dir = dirname($classFileName);
		foreach (Finder::findFiles($this->version . '*' . $affix . '.sql')->in($dir) as $file) {
			$this->executeOneSqlFile($file);
		}

	}

	private function executeOneSqlFile($filename)
	{
		echo '    Loading SQL file: ' . $filename->getBaseName('.sql');
		$this->getContainer()['lean']->loadFile($filename);
		echo " ....done \n";
	}



}
