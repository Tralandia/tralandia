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

	public function onAcceptedTranslations(Language $language, $wordsCountToPay)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($language, $wordsCountToPay);
		$body = $emailCompiler->compileBody();
		$user = $language->getTranslator();


		$message->setSubject($emailCompiler->compileSubject());
		$message->setHtmlBody($body);

		$message->setFrom('paradeiser@tralandia.com');
		$message->addTo($user->getLogin());

		$this->mailer->send($message);
	}


	private function prepareCompiler(Language $language, $wordsCount)
	{
		$user = $language->getTranslator();
		$emailCompiler = $this->createCompiler($user->getPrimaryLocation(), $user->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('dictionary-translations-accepted'));

		$emailCompiler->addTranslator('translator', $user);
		$emailCompiler->addLanguage('language', $language);
		$emailCompiler->addCustomVariable('wordCount', $wordsCount);
		$emailCompiler->addCustomVariable('amount', $language->getTranslationPriceForWords($wordsCount));

		return $emailCompiler;
	}

}
