<?php

namespace AdminModule;


use Entity\User\Role;

class PhrasePresenter extends BasePresenter {

	/**
	 * @autowire
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	protected $simpleFormFactory;

	/**
	 * @autowire
	 * @var \ResultSorter
	 */
	protected $resultSorter;

	/**
	 * @autowire
	 * @var \SupportedLanguages
	 */
	protected $supportedLanguages;

	protected $phraseRepositoryAccessor;

	public $phrase;

	public function injectDic(\Nette\DI\Container $dic) {
		$this->phraseRepositoryAccessor = $dic->phraseRepositoryAccessor;
	}

}
