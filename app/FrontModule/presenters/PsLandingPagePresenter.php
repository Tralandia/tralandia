<?php

namespace FrontModule;

use Tralandia\Dictionary\Translatable;

class PsLandingPagePresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Nette\Mail\IMailer
	 */
	protected $mailer;


	public function renderDefault()
	{
	}


	protected function createComponentOrderForm()
	{
		$form = $this->simpleFormFactory->create();

		$form->addText('name', '587')
			->setRequired('791');

		$form->addText('email', '580')
			->setRequired('791')
			->addRule($form::EMAIL, '791');

		$services = [
			'free' => 1129,
			'premium' => $this->translate(804153) . ' (100.00 €)',
		];
		$form->addSelect('service', 'a59', $services)
			->setPrompt('415')
			->setRequired('791');

		$form->addTextArea('message', '610', NULL, 6);

		$form->addSubmit('submit', '407');

		$form->onSuccess[] = $this->processOrderForm;

		return $form;
	}


	public function processOrderForm($form)
	{
		$values = $form->getValues();


		$body = 'Meno: ' . $values->name . '<br/>
		email: ' . $values->email . '<br/>
		balik: ' . $values->service . '<br/>
		sprava: ' . nl2br($values->message) . '<br/>';


		$message = new \Nette\Mail\Message();

		$message->setSubject('Nova objednavka s tralandia.com/website');
		$message->setHtmlBody($body, FALSE);

		$message->addTo('toth.radoslav@gmail.com');

		$this->mailer->send($message);

		if($this->isAjax()) {
			$this->template->orderSent = TRUE;
			$this->invalidateControl('orderForm');
		} else {
			$this->redirect('this');
		}
	}
}
