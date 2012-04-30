<?php

namespace AdminModule;

use Nette\ArrayHash,
	Tra\Utils\Arrays;

class RouterTestPresenter extends BasePresenter {

	public $urlsForTest;

	public function renderDefault() {
		$router = new \Extras\Route($this->context->routerCache, array(
										'presenter' => 'David',
										'action' => 'default',
										'country' => 'SK',
									));

		$testResults = ArrayHash::from(array());
		foreach ($this->getUrlsForTest() as $testName => $test) {
			$bugs = 0;
			$testResult = ArrayHash::from(array());

			$scriptUrl = new \Nette\Http\UrlScript($test->from);
			$httpRequest = new \Nette\Http\Request($scriptUrl, $scriptUrl->getQuery(), $post = NULL, $files = NULL, $cookies = NULL, $headers = NULL, $method = 'GET', $remoteAddress = NULL, $remoteHost = NULL);


			$appRequest = $router->match($httpRequest);


			$refUrl = new \Nette\Http\Url($httpRequest->getUrl());
			$refUrl->setPath($httpRequest->getUrl()->getScriptPath());

			$constructedUrl = $router->constructUrl($appRequest, $refUrl);

			if(array_key_exists('presenter', $test) && $test->presenter !== $appRequest->getPresenterName()) {
				$testResult->presenter = ArrayHash::from(array('expected' => $test->presenter, 'value' => $appRequest->getPresenterName()));
				$bugs++;
			} else {
				$testResult->presenter = $appRequest->getPresenterName();
			}

			if(array_key_exists('to', $test) && $test->to != $constructedUrl) {
				$testResult->to = ArrayHash::from(array('expected' => $test->to, 'value' => $constructedUrl));
				$bugs++;
			} else {
				$testResult->to = $constructedUrl;
			}

			if(array_key_exists('params', $test)) {
				foreach ($test->params as $name => $value) {
					$appValue = Arrays::get($appRequest, $key, NULL);
					if($value != $appValue) {
						$testResult->$key = ArrayHash::from(array('expected' => $value, 'value' => $appValue));
						$bugs++;
					} else {
						$testResult->$key = $appValue;
					}
				}
			}

			$testResult->bugs = $bugs;
			$testResults->$testName = $testResult;
		}

		$newOrder = array();
		foreach ($testResults as $key => $value) {
			if($value->bugs > 0) {
				$newOrder['wrong'][$key] = $value;
			} else {
				$newOrder['correct'][$key] = $value;
			}
		}

		$this->template->testResults = ArrayHash::from($newOrder['wrong'] + $newOrder['correct']);
	}

	public function getUrlsForTest() {
		if(!$this->urlsForTest) {
			$config = new \Nette\Config\Loader;

			$this->urlsForTest = ArrayHash::from($config->load(APP_DIR . '/configs/router/urlsForTest.neon', 'common'));
		}
		return $this->urlsForTest;
	}

}
