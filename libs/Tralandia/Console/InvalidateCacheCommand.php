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

class InvalidateCacheCommand extends BaseCommand
{



	protected function configure()
	{
		$this->setName('invalidateCache');

//		$this->addArgument('emailType', InputArgument::REQUIRED, 'aky email sa ma posielat? [updateYourRental|potentialMember|backlink]');
//
//		$this->addOption('time', 't', InputOption::VALUE_REQUIRED, 'Dlzka trvania (v sec.)', 11);
//		$this->addOption('reset', NULL, InputOption::VALUE_NONE);
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{

		/** @var $db \DibiConnection */
		$db = $this->getHelper('dic')->getContainer()->getService('dibiConnection');

		$now = new \DateTime();
		$query = 'delete from [template_cache] where [expiration] < %d';

		$affectedRows = $db->query($query, $now);

		$this->report("$affectedRows template cache row(s) deleted.");
	}


}
