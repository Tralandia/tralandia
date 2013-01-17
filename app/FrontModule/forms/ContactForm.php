<?php

namespace FrontModule\Forms\Rental;

use Nette;

/**
 * ContactForm class
 *
 * @author Dávid Ďurika
 */
class ContactForm extends \FrontModule\Forms\BaseForm {

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	public function __construct(\Entity\User\User $user = NULL)
	{
		$this->user = $user;

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

		$this->onSuccess[] = callback($this, 'process');
	}

	public function setDefaultsValues() 
	{
		
	}

	public function process(ContactForm $form)
	{
		$values = $form->getValues();
	}

}