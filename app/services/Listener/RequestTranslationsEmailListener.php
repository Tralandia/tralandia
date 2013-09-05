<?php
namespace Listener;

use AdminModule\DictionaryManagerPresenter;
use Entity\Language;
use Entity\User\User;
use Environment\Environment;
use Nette;

class RequestTranslationsEmailListener extends BaseEmailListener
{

	public function getSubscribedEvents()
	{
		return ['AdminModule\Grids\Dictionary\ManagerGrid::onRequestTranslations'];
	}

	public function onRequestTranslations(Language $language, $wordsCountToPay, User $requestedBy)
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
		$emailCompiler->setTemplate($this->getTemplate('dictionary-request-translations'));

		$deadline = (new Nette\DateTime())->modify('+2 days')->format('Y-m-d');
		$emailCompiler->addTranslator('translator', $user);
		$emailCompiler->addLanguage('language', $language);
		$emailCompiler->addCustomVariable('wordCount', $wordsCount);
		$emailCompiler->addCustomVariable('totalPrice', $language->getTranslationPriceForWords($wordsCount));
		$emailCompiler->addCustomVariable('deadline', $deadline);

		return $emailCompiler;
	}

}
