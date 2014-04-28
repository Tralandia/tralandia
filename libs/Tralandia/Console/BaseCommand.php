<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 9/11/13 1:27 PM
 */

namespace Tralandia\Console;


use Nette;
use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command {


	public function report($message)
	{
		$print = $message;
		if(is_array($message)) {
			$print = implode("\n", $message);
		}
		echo $print . "\n";

		$log = $message;
		if(is_array($message)) {
			$log = implode(" | ", $message);
		}
		Nette\Diagnostics\Debugger::log($log, 'command-report');
	}

}
