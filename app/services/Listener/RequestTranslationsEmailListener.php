<?php
namespace Listener;

use AdminModule\DictionaryManagerPresenter;
use Entity\Language;
use Environment\Environment;
use Nette;

class RequestTranslationsEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['AdminModule\DictionaryManagerPresenter::onRequestTranslations'];
	}

	public function onRequestTranslations(Language $language)
	{
		$message = new \Nette\Mail\Message();

		$emailCompiler = $this->prepareCompiler($language);
		$body = $emailCompiler->compileBody();

		$user = $language->getTranslator();

		$message->setHtmlBody($body);
		$message->addTo($user->getLogin());

		$this->mailer->send($message);
	}


	private function prepareCompiler(Language $language)
	{
		$user = $language->getTranslator();
		$emailCompiler = $this->getCompiler($user->getPrimaryLocation(), $user->getLanguage());
		$emailCompiler->setTemplate($this->getTemplate('dictionary-request-translations'));
		$emailCompiler->setLayout($this->getLayout());

		return $emailCompiler;
	}

}
