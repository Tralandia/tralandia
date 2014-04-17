<?php

/**
 * Načítá nastavení aplikace pro danou školu
 */
trait ApplicationSettings
{

	private function getSchoolShortcut($connection)
	{
		return $connection->application_settings(['name' => 'school_shortcut'])->fetch()['value'];
	}


	private function updateSettings($connection, $name, $value)
	{
		$setting = $connection->application_settings(['name' => $name])->fetch();
		$setting->update(['value' => $value]);
	}

}
