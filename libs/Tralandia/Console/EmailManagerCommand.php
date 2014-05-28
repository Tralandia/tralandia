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

class EmailManagerCommand extends BaseCommand
{

	/**
	 * @var int
	 */
	private $time;

	/**
	 * @var int
	 */
	private $endsAt;

	/**
	 * @var \Tralandia\Console\EmailManager\EmailManager
	 */
	private $emailManager;


	protected function configure()
	{
		$this->setName('email:manager');

		$this->addArgument('emailType', InputArgument::REQUIRED, 'aky email sa ma posielat? [updateYourRental|potentialMember|backlink|newsletter]');

		$this->addOption('time', 't', InputOption::VALUE_REQUIRED, 'Dlzka trvania (v sec.)', 11);
		$this->addOption('reset', NULL, InputOption::VALUE_NONE);
		$this->addOption('count', NULL, InputOption::VALUE_NONE);
	}


	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$emailType = $input->getArgument('emailType');

		if($emailType == 'updateYourRental') {
			$this->emailManager = $this->getHelper('dic')->getByType('\Tralandia\Console\EmailManager\UpdateYourRental');
		} else if($emailType == 'potentialMember') {
			$this->emailManager = $this->getHelper('dic')->getByType('\Tralandia\Console\EmailManager\PotentialMember');
		} else if($emailType == 'backlink') {
			$this->emailManager = $this->getHelper('dic')->getByType('\Tralandia\Console\EmailManager\Backlink');
		} else if($emailType == 'newsletter') {
			$this->emailManager = $this->getHelper('dic')->getByType('\Tralandia\Console\EmailManager\Newsletter');
		} else {
			return 1;
		}

		$emailManager = $this->emailManager;

		$this->time = $input->getOption('time');
		$this->endsAt = time() + $this->time - 10;

		$message = [];
		$message[] = '';
		$message[] = 'Email Manager';
		$message[] = '-----------------';
		$message[] = 'type: ' . $emailManager::NAME;
		$message[] = 'time: -t ' . $this->time;
		$message[] = 'ends at: ' . strftime('%T %F', $this->endsAt);
		$message[] = '---------------------------';
		$message[] = '';

		foreach($message as $line) {
			$output->writeLn($line);
		}

		\Nette\Diagnostics\Debugger::log(implode(' ', $message), $emailManager::NAME);
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{

		$emailManager = $this->emailManager;
		try {

			if(!$emailManager) {
				return 1;
			}

			if($input->getOption('reset')) {
				$this->log($output, '-------- resetting --------', $emailManager::NAME);
				$emailManager->resetManager();
				$this->log($output, '-------- done --------', $emailManager::NAME);
				return 0;
			}

			if($input->getOption('count')) {
				$count = $emailManager->toSentCount();
				$totalCount = $emailManager->totalCount();
				$this->log($output, $count . ' emails left', $emailManager::NAME);
				$this->log($output, "of $totalCount", $emailManager::NAME);
				$this->log($output, '-------- done --------', $emailManager::NAME);
				return 0;
			}

			/** @var $environment \Environment\Environment */
			$environment = $this->getHelper('dic')->getByType('\Environment\Environment');

			while($this->endsAt > time()) {
				$this->log($output, '--------', $emailManager::NAME);

				$emailManager->next();
				if($emailManager->isEnd()) break;

				$email = $emailManager->getEmail();
				if(Nette\Utils\Validators::isEmail($email)) {
					$this->log($output, 'id: ' . $emailManager->getRowId() . ', email: ' . $email, $emailManager::NAME);
					$emailManager->resetEnvironment($environment);

					$emailManager->send();
				} else {
					$this->log($output, 'WRONG email: ' . $email, $emailManager::NAME);
					$emailManager->wrongEmail();
				}


				$this->log($output, 'sent', $emailManager::NAME);
				sleep(1);
			}

			$this->log($output, '------------- THE END -------------', $emailManager::NAME);
			return 0;
		} catch(\Exception $e) {
			\Nette\Diagnostics\Debugger::log('error: ' . $e->getMessage(), $emailManager::NAME);
			\Nette\Diagnostics\Debugger::log($e);
			return 1;
		}
	}


	protected function log(OutputInterface $output, $message, $type)
	{
		$output->writeln($message);
		\Nette\Diagnostics\Debugger::log($message, $type);
	}


}
