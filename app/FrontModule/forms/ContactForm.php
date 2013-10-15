<?php

namespace FrontModule\Forms;

use Nette;

/**
 * ContactForm class
 *
 * @author Dávid Ďurika
 */
class ContactForm extends BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Entity\Language
	 */
	protected $language;

	public $ticketRepository;

	public function __construct(\Entity\User\User $user = NULL, \Entity\Language $language = NULL, $ticketDao)
	{
		$this->user = $user;
		$this->language = $language;

		if(!$user && !$language) {
			throw new \Nette\InvalidArgumentException;
		}

		$this->ticketRepository = $ticketDao;

		parent::__construct();
	}

	public function buildForm()
	{
		if($this->user) {
			$user = $this->user;
			if($user->getRentals()->count()) {
				$items = array();
				foreach($user->getRentals() as $rental) {
					$items[$rental->getId()] = $rental->getName()->getTranslationText($user->getLanguage());
				}
				$this->addSelect('rental', '#Rental', $items);
			}
		}

		$this->addText('email', '#Email')
			->addRule(self::EMAIL, '#Toto nieje email');

		$this->addTextArea('message', '#Message');

		$this->addSubmit('submit', 'o1719');
		$this->addButton('cancel', '#cancel');

		$this->onSuccess[] = callback($this, 'process');
	}

	public function setDefaultsValues()
	{

	}

	public function process(ContactForm $form)
	{
		$values = $form->getValues();
		$messageRepository = $this->ticketRepository->related('messages');

		/** @var $user \Entity\User\User */
		if($this->user) {
			$user = $this->user;
			$language = $user->getLanguage();
		} else {
			$userRepository = $messageRepository->related('from');

			if(!$user = $userRepository->findOneByLogin($values->email)) {
				$user = $userRepository->createNew();
				$user->setLanguage($this->language);
				$user->setLogin($values->email);
				$userRepository->persist($user);
			}
		}

		/** @var $ticket \Entity\Ticket\Ticket */
		$ticket = $this->ticketRepository->createNew();
		$ticket->setLanguage($user->getLanguage());

		/** @var $message \Entity\Ticket\Message */
		$message = $messageRepository->createNew();
		$message->setMessage($values->message);
		$message->setFrom($user);

		$ticket->addMessage($message);


		$this->ticketRepository->save($ticket);
	}

}

interface IContactFormFactory {

	/**
	 * @param \Entity\User\User $user
	 * @param \Entity\Language $language
	 *
	 * @return ContactForm
	 */
	public function create(\Entity\User\User $user = NULL, \Entity\Language $language = NULL);
}
