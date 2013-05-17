<?php

namespace AdminModule;


use Nette\Application\Responses\TextResponse;

class EmailTesterPresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \Nette\Mail\IMailer
	 */
	protected $mailer;

	/**
	 * @var array
	 */
	protected $sendTestTo = [
		'demo.gregor@gmail.com', 'lubiscukriky@yahoo.com','kaligulanebezpecny@hotmail.com'
	];

	protected $bodyTemplateFile = '/emails/template1.html';

	public function actionSendTemplate()
	{
		$this->template->sendTestTo = $this->sendTestTo;
	}

	public function handleSend()
	{
		$message = new \Nette\Mail\Message();

		$bodyHtml = file_get_contents(WWW_DIR . $this->bodyTemplateFile);
		$message->setHtmlBody($bodyHtml);

		$sendTestTo = $this->sendTestTo;
		$to = array_shift($sendTestTo);

		$message->addTo($to);
		foreach($sendTestTo as $bcc) {
			$message->addBcc($bcc);
		}

		$this->mailer->send($message);

		$this->flashMessage('Email SENT!');
		$this->redirect('sendTemplate');
	}


	public function actionViewTemplate()
	{
		$bodyHtml = file_get_contents(WWW_DIR . $this->bodyTemplateFile);
		$response = new TextResponse($bodyHtml);
		$this->sendResponse($response);
	}


}
