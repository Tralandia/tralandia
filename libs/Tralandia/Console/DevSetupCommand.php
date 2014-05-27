<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/11/13 13:30
 */

namespace Tralandia\Console;


use Nette;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DevSetupCommand extends BaseCommand
{


	/**
	 * @var \LeanMapper\Connection
	 */
	protected $db;


	protected function configure()
	{
		$this->setName('dev:setup');

//		$this->addArgument('emailType', InputArgument::REQUIRED, 'aky email sa ma posielat? [updateYourRental|potentialMember|backlink]');
//
//		$this->addOption('time', 't', InputOption::VALUE_REQUIRED, 'Dlzka trvania (v sec.)', 11);
//		$this->addOption('reset', NULL, InputOption::VALUE_NONE);
	}


	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$this->db = $this->getHelper('dic')->getByType('\LeanMapper\Connection');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$query = 'SELECT id, url FROM personalsite_configuration WHERE url IS NOT NULL and url != "" AND id < 500';
		$result = $this->db->query($query)->fetchAll();
		foreach($result as $row) {
			$url = Nette\Utils\Strings::replace($row['url'], [
				'~\.tralandia\.~' => '.tra-local.',
				'~\.uns\.~' => '.uns-local.',
			]);

			if(Nette\Utils\Strings::startsWith($url, 'www.')) {
				$l = explode('.', $url);
				array_pop($l);
				$l[] = 'local';
				$url = implode('.', $l);
			}

			$update = "update personalsite_configuration set url = '{$url}' where id = '{$row['id']}'";
			$this->db->query($update);
		}

	}


}
