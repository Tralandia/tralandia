<?php

namespace AdminModule;

use \Services as S,
	\Services\Dictionary as D;

class RouterTestPresenter extends BasePresenter {

	public function actionDefault() {

		$samples = array(
			'http://www.tra.sk/mala-fatra/chalupy/prazdninovy-pobyt?lfPeople=6&lfFood=6' => 'http://www.tra.sk/mala-fatra/chalupy/prazdninovy-pobyt?lfPeople=6&lfFood=6',
			'http://www.tra.sk/chaty/nitra-okres/prazdninovy-pobyt?lfPeople=6&lfFood=6' => 'http://www.tra.sk/nitra-okres/chaty/prazdninovy-pobyt?lfPeople=6&lfFood=6',
			'http://www.tra.sk/chaty/nitra-okres/prazdninovy-pobyt?lfPeople=6&lfFood=4' => 'http://www.tra.sk/nitra-okres',
		);

		$router = new \Extras\Route($this->context->routerCache, array(
										'presenter' => 'David',
										'action' => 'default',
										'country' => 'SK',
									));

		$testResult = array();
		foreach ($samples as $key => $value) {
			$scriptUrl = new \Nette\Http\UrlScript($key);
			$httpRequest = new \Nette\Http\Request($scriptUrl, $scriptUrl->getQuery(), $post = NULL, $files = NULL, $cookies = NULL, $headers = NULL, $method = 'GET', $remoteAddress = NULL, $remoteHost = NULL);


			$appRequest = $router->match($httpRequest);


			$refUrl = new \Nette\Http\Url($httpRequest->getUrl());
			$refUrl->setPath($httpRequest->getUrl()->getScriptPath());

			$constructedUrl = $router->constructUrl($appRequest, $refUrl);

			if($value == $constructedUrl) {
				$testResult[] = 'OK';
			} else {
				$testResult[] = 'Chyba ----';
			}
		}


	}

}
