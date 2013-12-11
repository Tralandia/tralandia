<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 29/11/13 13:30
 */

namespace Tralandia\Console;


use Nette;
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
	 * @var \Tralandia\BaseDao
	 */
	private $rentalDao;

	/**
	 * @var \Listener\NotificationEmailListener
	 */
	private $notificationEmailListener;


	protected function configure()
	{
		$this->setName('email:manager');


		$this->addOption('time', 't', InputOption::VALUE_REQUIRED, 'Dlzka trvania (v sec.)', 11);
	}


	protected function initialize(InputInterface $input, OutputInterface $output)
	{
		$this->rentalDao = $this->getHelper('dic')->getByType('\Doctrine\ORM\EntityManager')->getRepository(RENTAL_ENTITY);
		$this->notificationEmailListener = $this->getHelper('dic')->getByType('\Listener\NotificationEmailListener');

		$this->time = $input->getOption('time');
		$this->endsAt = time() + $this->time - 10;

		$message = [];
		$message[] = '';
		$message[] = 'Email Manager';
		$message[] = '-----------------';
		$message[] = 'time: -t ' . $this->time;
		$message[] = 'ends at: ' . strftime('%T %F', $this->endsAt);
		$message[] = '---------------------------';
		$message[] = '';

		foreach($message as $line) {
			$output->writeLn($line);
		}

		\Nette\Diagnostics\Debugger::log(implode(' ', $message), 'email_manager');
	}


	protected function execute(InputInterface $input, OutputInterface $output)
	{


		try {
			/** @var $environment \Environment\Environment */
			$environment = $this->getHelper('dic')->getByType('\Environment\Environment');

			while($this->endsAt > time()) {
				$this->log($output, '--------', 'email_manager');

				$rental = $this->getNextRental();
				if(!$rental) break;

				$this->log($output, 'rental: ' . $rental->id . ', email: ' . $rental->getContactEmail(), 'email_manager');

				$user = $rental->getUser();
				$environment->resetTo($user->getPrimaryLocation(), $user->getLanguage());

				$this->notificationEmailListener->onSuccess($rental);

				$rental->emailSent = TRUE;
				$this->rentalDao->save($rental);

				$this->log($output, 'sent', 'email_manager');


				sleep(1);
			}

			$output->writeLn('------------- THE END -------------');
			return 0;
		} catch(\Exception $e) {
			\Nette\Diagnostics\Debugger::log('error: ' . $e->getMessage(), 'email_manager');
			return 1;
		}
	}


	/**
	 * @return \Entity\Rental\Rental|null
	 */
	protected function getNextRental()
	{
		$qb = $this->rentalDao->createQueryBuilder('r');
		$qb->where($qb->expr()->eq('r.emailSent', ':emailSent'))->setParameter('emailSent', FALSE)
//			->andWhere($qb->expr()->eq('r.harvested', ':harvestedOnly'))->setParameter('harvestedOnly', TRUE)
			->setMaxResults(1);

		$rental = $qb->getQuery()->getOneOrNullResult();

		return $rental;
	}


	protected function log(OutputInterface $output, $message, $type)
	{
		$output->writeln($message);
		\Nette\Diagnostics\Debugger::log($message, $type);
	}


}
