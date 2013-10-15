<?php

namespace FrontModule\Forms;

use Nette;

/**
 * TicketForm class
 *
 * @author Dávid Ďurika
 */
class TicketForm extends BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \Entity\Ticket\Ticket
	 */
	protected $ticket;

	protected $messageRepository;
	protected $questionRepository;

	public function __construct(\Entity\User\User $user, \Entity\Ticket\Ticket $ticket, $messageDao,
								$questionRepository)
	{
		$this->user = $user;
		$this->ticket = $ticket;
		$this->messageRepository = $messageDao;
		$this->questionRepository = $questionRepository;

		parent::__construct();
	}

	public function buildForm()
	{

		if($this->userIsAdmin()) {
			$items = array();
			foreach($this->questionRepository->findAll() as $question) {
				/** @var $question \Entity\Faq\Question */
				$category = $question->getCategory()->getName()->getTranslationText($this->user->getLanguage());
				$items[$category][$question->getId()] = $question->getQuestion()->getTranslationText($this->user->getLanguage
				());
			}
			$this->addSelect('canned', '#Canned', $items)
				->setPrompt('{_o100063}');
		}

		$this->addTextArea('message', '#Message');
		$this->addUpload('attachment', '#Attachment');

		$this->addSubmit('submit', '#Send');

		$this->onSuccess[] = callback($this, 'process');
	}

	public function setDefaultsValues()
	{

	}

	public function process(TicketForm $form)
	{
		$values = $form->getValues();
		d($values);

		if($this->userIsAdmin()) {
			if($values->canned) {
				/** @var $answer \Entity\Phrase\Phrase */
				$answer = $this->questionRepository->find($values->canned)->getAnswer();
				$message = $answer->getTranslationText($this->ticket->getLanguage());
				$messageEn = $answer->getCentralTranslationText();
			}
		}
		if(!isset($message)) {
			$message = $values->message;
		}

		/** @var $messageEntity \Entity\Ticket\Message */
		$messageEntity = $this->messageRepository->createNew();
		$messageEntity->setMessage($message);
		if(isset($messageEn)) $messageEntity->setMessageEn($messageEn);
		$messageEntity->setFrom($this->user);

		$this->ticket->addMessage($messageEntity);

		$this->messageRepository->flush($this->ticket);
	}

	protected function userIsAdmin()
	{
		return $this->user->hasRole(['admin', 'superadmin']);
	}

}

interface ITicketFormFactory {
	/**
	 * @param \Entity\User\User $user
	 * @param \Entity\Ticket\Ticket $ticket
	 *
	 * @return TicketForm
	 */
	public function create(\Entity\User\User $user, \Entity\Ticket\Ticket $ticket);
}
