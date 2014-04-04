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

class CleanUpLocationsCommand extends BaseCommand
{


	/**
	 * @var \LeanMapper\Connection
	 */
	protected $db;


	protected function configure()
	{
		$this->setName('cleanUp:locations');

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
		$query = 'DELETE l FROM location AS l
LEFT JOIN contact_address a ON a.locality_id = l.id
WHERE a.id IS NULL AND l.type_id = 5';
		$this->db->query($query);
	}


}
