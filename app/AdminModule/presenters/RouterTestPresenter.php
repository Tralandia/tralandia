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

			if(array_key_exists('params', $test)) {
				$appParams = $appRequest->getParameters();
				$testParams = $test->params;
				foreach ($appParams as $name => $appValue) {
					$value = Arrays::get($testParams, $name, NULL);
					unset($testParams[$name]);
					if($value != $appValue) {
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
				foreach ($testParams as $name => $value) {
					$testResult->params->$name = ArrayHash::from(array('expected' => $value, 'value' => 'NULL'));
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
