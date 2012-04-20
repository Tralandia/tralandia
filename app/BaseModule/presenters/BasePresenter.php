<?php

use Nette\Application\UI\Presenter,
	Nette\Environment,
	Nette\Utils\Finder;


abstract class BasePresenter extends Presenter {

	public function beforeRender() {
		parent::beforeRender();
		$this->template->staticPath = '/';
		$this->template->setTranslator($this->getService('translator'));
		$this->template->registerHelper('image', callback('Tools::helperImage'));
	}

	protected function createTemplate($class = NULL) {
		$template = parent::createTemplate($class);
		$template->registerHelper('ulList', callback($this, 'ulListHelper'));
		return $template;
	}

	protected function createComponentFlashes($name) {
		return new FlashesControl($this, $name);
	}

	public function getParams() {
		return (object)$this->getParam();
	}

	public function flashMessage($message, $type = 'info') {
		parent::flashMessage($message, $type);
		$this->getComponent('flashes')->invalidateControl();
	}

	public function invalidateFlashMessage() {
		$this->getComponent('flashes')->invalidateControl();
	}

	public function getEntityManager() {
		return $this->context->entityManager;
	}

	public function getEm() {
		return $this->getEntityManager();
	}

	public function getEnvironment() {
		return $this->getService('environment');
	}

	protected function createComponentHeader() {
		$header = new HeaderControl;

		list($modul, $presenter) = explode(':', $this->name, 2);

		$header->setDocType(HeaderControl::HTML_5);
		$header->setLanguage(HeaderControl::SLOVAK);
		
		$header->setTitle('Tralandia');

		// facebook xml namespace
		//$header->htmlTag->attrs['xmlns:fb'] = 'http://www.facebook.com/2008/fbml';

		$header->setTitleSeparator(' - ')
				->setTitlesReverseOrder(true)
				->setFavicon('favicon.ico')
				->addKeywords(array())
				->setDescription('')
				->setRobots('index,follow')
				->setAuthor('Tralandia ltd.');


		return $header;
	}

	public function createComponentCss() {
		list($modul, $presenter) = explode(':', $this->name, 2);
		
		$files = new \WebLoader\FileCollection(WWW_DIR . '/styles');
		$files->addFiles(Finder::findFiles('*.css', '*.less')->in(WWW_DIR . '/styles'));
		$files->addFiles(Finder::findFiles('*.css', '*.less')->in(WWW_DIR . '/styles/'.strtolower($modul)));

		$compiler = \WebLoader\Compiler::createCssCompiler($files, WWW_DIR . '/webtemp');
		$compiler->addFileFilter(new \Webloader\Filter\LessFilter());

		return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
	}

	public function createComponentJs() {
		list($modul, $presenter) = explode(':', $this->name, 2);

		$files = new \WebLoader\FileCollection(WWW_DIR . '/scripts');
		$files->addFiles(Finder::findFiles('*.js')->in(WWW_DIR . '/scripts'));
		$files->addFiles(Finder::findFiles('*.js')->in(WWW_DIR . '/scripts/'.strtolower($modul)));

		$compiler = \WebLoader\Compiler::createJsCompiler($files, WWW_DIR . '/webtemp');

		return new \WebLoader\Nette\JavaScriptLoader($compiler, $this->template->basePath . '/webtemp');
	}

	public function templatePrepareFilters($template) {
		$latte = new \Nette\Latte\Engine;
		$template->registerFilter($latte);
		\Extras\Macros::install($latte->compiler);
	}

	public function getBaseUrl() {
		return $this->template->baseUrl;
	}

	/* --------------------- Helpers --------------------- */

	public function ulListHelper($data, $columnCount = 3, $li = NULL, $columnLimit = 0) {
		if(!($data instanceof \Traversable || is_array($data))) {
			throw new \Nette\InvalidArgumentException('Argument "$data" does not match with the expected value');
		}

		if(!is_numeric($columnCount) || $columnCount <= 0) {
			throw new \Nette\InvalidArgumentException('Argument "$columnCount" does not match with the expected value');
		}

		if($li === NULL) {
			$li = '<li>%name% - {_123}</li>';
		}

		preg_match_all('/%[a-zA-Z\._]+%/', $li, $matches);

		$replaces = array();

		foreach ($matches[0] as $match) {

			$translate = false;
			$matchKey = $match;
			if (strpos($match, '_')) {
				$translate = true;
				$matchKey = str_replace('_', '', $match);
			}

			if (gettype($data)=='object') {
				$value = '$item->'.str_replace('.', '->', substr($matchKey, 1, -1));
			} else {
				$value = '$item["'.str_replace('.', '"]["', substr($matchKey, 1, -1)).'"]';
			}

			if ($translate) {
				$value = '$this->template->translate('.$value.')';
			}

			$replaces[$match] = $value;
		}

		$newData = array();

		$i=1;
		$counter=0;
		foreach ($data as $k=>$item) {
			$search = array();
			$replace = array();
			foreach ($replaces as $key => $value) {
				$search[] = $key;
				eval('$r = '.$value.';');
				$replace[] = $r;
			}
			$liTemp = str_replace($search, $replace, $li);
			$row = ($i<=$columnCount)?$i:$i=1;
			$newData[$row][] = $liTemp;
			$i++;
			$counter++;
			if ($columnLimit!=0 && ($columnLimit*$columnCount)<=$counter) break;
		}

		$html = \Nette\Utils\Html::el();
		foreach ($newData as $key => $value) {
			$ul = \Nette\Utils\Html::el('ul');
			$ul->add(implode('', $value));
			$html->add($ul);
		}

		return $html;
	}

}
