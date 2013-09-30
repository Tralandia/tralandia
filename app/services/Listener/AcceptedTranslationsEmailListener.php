<?php
namespace Listener;

use AdminModule\DictionaryManagerPresenter;
use Entity\Language;
use Entity\User\User;
use Environment\Environment;
use Nette;

class AcceptedTranslationsEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return [];
	}

	public function onAcceptedTranslations(Language $language, $totalAmount)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($language, $totalAmount);
		$body = $emailCompiler->compileBody();
		$user = $language->getTranslator();


		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body);

		$message->setFrom('paradeiser@tralandia.com');
		$message->addTo($user->getLogin());

		$this->mailer->send($message);
	}


	private function prepareCompiler(Language $language, $totalAmount)
	{
		$user = $language->getTranslator();
		$emailCompiler = $this->createCompiler($user->getPrimaryLocation(), $user->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('dictionary-translations-accepted'));

		$emailCompiler->addTranslator('translator', $user);
		$emailCompiler->addLanguage('language', $language);
		$emailCompiler->addCustomVariable('wordCount', NULL);
		$emailCompiler->addCustomVariable('amount', $totalAmount);

		return $emailCompiler;
	}

}
