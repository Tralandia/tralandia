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

	public function ulListHelper($data, $columnCount = 3, $li = NULL) {
		if(!($data instanceof \Traversable || is_array($data))) {
			throw new \Nette\InvalidArgumentException('Argument "$data" does not match with the expected value');
		}

		if(!is_numeric($columnCount) || $columnCount <= 0) {
			throw new \Nette\InvalidArgumentException('Argument "$columnCount" does not match with the expected value');
		}
		// @cibi ked chces nieco prelozit tak to zapis takto:
		// $text = $this->template->translate(123);
		if($li === NULL) {
			$li = '<li>%name% - {_123}</li>';
		}

		preg_match_all('/%[a-zA-Z\.]+%/', $li, $matches);

		$replaces = array();
		foreach ($matches[0] as $match) {
			if (gettype($data)=='object') {
				$value = '$item->'.str_replace('.', '->', substr($match, 1, -1));
			} else {
				$value = '$item["'.str_replace('.', '"]["', substr($match, 1, -1)).'"]';
			}
			$replaces[$match] = $value;
		}

		$newData = array();
		for ($i=0; $i < $columnCount; $i++) {
			foreach ($data as $k=>$item) {
				$search = array();
				$replace = array();
				foreach ($replaces as $key => $value) {
					$search[] = $key;
					eval('$r = '.$value.';');
					$replace = $r;
				}
				$liTemp = str_replace($search, $replace, $li);
				$newData[$i][] = $liTemp;
				unset($data[$k]);
				break;
			}
		}

		$return = array();
		foreach ($newData as $key => $value) {
			$return[] = '<ul>'.implode('', $value).'</ul>';
		}

		return implode('', $return);
	}

}
