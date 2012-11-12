<?php

namespace AdminModule;

use Nette\ArrayHash,
	Tra\Utils\Arrays,
	Nette\Utils\Validators;

class RouterTestPresenter extends BasePresenter {

	public $urlsForTest;


	public function actionDefault($url) {
		$urlForm = $this->getComponent('urlForm');
		if($url) {
			$this->setUrlsForTest($url);
			$urlForm['url']->setDefaultValue($url);
		}
	}

	public function renderTest() {
		$url = 'http://www.tra.sk/mala-fatra/chalupy/prazdninovy-pobyt?lfPeople=6&lfFood=6';
		$scriptUrl = new \Nette\Http\UrlScript($url);
		$httpRequest = new \Nette\Http\Request($scriptUrl, $scriptUrl->getQuery(), $post = NULL, $files = NULL, $cookies = NULL, $headers = NULL, $method = 'GET', $remoteAddress = NULL, $remoteHost = NULL);

		$request = $this->context->router->match($httpRequest);
		debug($request);
	}

	public function renderDefault() {
		$router = new \Extras\Route($this->context->routerCache, array(
										'presenter' => 'David',
										'action' => 'default',
										'country' => 'SK',
									));

		$testResults = ArrayHash::from(array());
		foreach ($this->getUrlsForTest() as $testName => $test) {
			$bugs = 0;
			$testResult = ArrayHash::from(array(
				'from' => $test->from,
				'params' => array(),
			));

			$scriptUrl = new \Nette\Http\UrlScript($test->from);
			$httpRequest = new \Nette\Http\Request($scriptUrl, $scriptUrl->getQuery(), $post = NULL, $files = NULL, $cookies = NULL, $headers = NULL, $method = 'GET', $remoteAddress = NULL, $remoteHost = NULL);


			$appRequest = $router->match($httpRequest);


			$refUrl = new \Nette\Http\Url($httpRequest->getUrl());
			$refUrl->setPath($httpRequest->getUrl()->getScriptPath());

			$constructedUrl = $router->constructUrl($appRequest, $refUrl);

			if(array_key_exists('presenter', $test) && $test->presenter !== $appRequest->getPresenterName()) {
				$testResult->params->presenter = ArrayHash::from(array('expected' => $test->presenter, 'value' => $appRequest->getPresenterName()));
				$bugs++;
			} else {
				$testResult->params->presenter = $appRequest->getPresenterName();
			}

			if(array_key_exists('to', $test) && $test->to != $constructedUrl) {
				$testResult->to = ArrayHash::from(array('expected' => $test->to, 'value' => $constructedUrl));
				$bugs++;
			} else {
				$testResult->to = $constructedUrl;
			}

			$testParams = Arrays::get($test, 'params', FALSE);
			$appParams = $appRequest->getParameters();
			if($testParams === FALSE) {
				$compareParams = FALSE;
			} else {
				$compareParams = TRUE;
			}
			foreach ($appParams as $name => $appValue) {
				if($compareParams) {
					$value = Arrays::get($testParams, $name, NULL);
					unset($testParams[$name]);
				}
				if($compareParams && $value != $appValue) {
					if($appValue === NULL) $appValue = 'NULL';
					if($appValue === FALSE) $appValue = 'FALSE';
					if($appValue === TRUE) $appValue = 'TRUE';
					if($value === NULL) $value = 'NULL';
					if($value === FALSE) $value = 'FALSE';
					if($value === TRUE) $value = 'TRUE';
					$testResult->params->$name = ArrayHash::from(array('expected' => $value, 'value' => $appValue));
					$bugs++;
				} else {
					$testResult->params->$name = $appValue;
				}
			}
			if(is_array($testParams)) {
				foreach ($testParams as $name => $value) {
					$testResult->params->$name = ArrayHash::from(array('expected' => $value, 'value' => 'NULL'));
					$bugs++;
				}
			}

			$testResult->bugs = $bugs;
			$testResults->$testName = $testResult;
		}

		$newOrder = array(
			'wrong' => array(),
			'correct' => array(),
		);
		foreach ($testResults as $key => $value) {
			if($value->bugs > 0) {
				$newOrder['wrong'][$key] = $value;
			} else {
				$newOrder['correct'][$key] = $value;
			}
		}

		$this->template->wrongUrlsCount = count($newOrder['wrong']);
		$this->template->testResults = ArrayHash::from($newOrder['wrong'] + $newOrder['correct']);
	}

	public function createComponentUrlForm($name) {
		$form = new \Nette\Application\UI\Form($this, $name);
		$form->addText('url', 'Url')
			->addRule(\Nette\Application\UI\Form::URL, 'Url is not valid :/');
		$form->addSubmit('submit', 'Submit');
		$form->addButton('clear', 'Clear');
		$form->onSuccess[] = callback($this, 'urlFormOnSuccess');
		return $form;
	}

	public function urlFormOnSuccess($form) {
		$values = $form->getValues();
		$this->redirect('RouterTest:default', array('url' => $values->url));
	}

	public function setUrlsForTest($urls = NULL) {
		if(is_string($urls)) {
			$this->urlsForTest = ArrayHash::from(array(
				'url' => array('from' => $urls),
			));
		} else {
			$config = new \Nette\Config\Loader;

			$this->urlsForTest = ArrayHash::from($config->load(APP_DIR . '/configs/router/urlsForTest.neon', 'common'));
		}
	}

	public function getUrlsForTest() {
		if(!$this->urlsForTest) {
			$this->setUrlsForTest();
		}
		return $this->urlsForTest;
	}

}
