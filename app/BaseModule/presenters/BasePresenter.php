<?php

use Nette\Application\UI\Presenter,
	Nette\Environment,
	Nette\Utils\Finder,
	Nette\Security\User;


abstract class BasePresenter extends Presenter {

	public $cssFiles;
	public $jsFiles;

	protected function startup() {
		parent::startup();
		
		$backlink = $this->storeRequest();
		// if (false /*!$this->user->isLoggedIn()*/) {
		// 	if ($this->user->getLogoutReason() === User::INACTIVITY) {
		// 		$this->flashMessage('Session timeout, you have been logged out', 'warning');
		// 	}

		// 	$backlink = $this->getApplication()->storeRequest();
		// 	$this->redirect('Auth:login', array('backlink' => $backlink));
		// } else {
			if (!$this->user->isAllowed($this->name, $this->action)) {
				$this->flashMessage('Access diened. You don\'t have permissions to view that page.', 'warning');
				// $this->redirect('Auth:login');
				throw new \Nette\MemberAccessException('co tu chces?!');
			}
		// }
		// odstranuje neplatne _fid s url
		if (!$this->hasFlashSession() && !empty($this->params[self::FLASH_KEY])) {
			unset($this->params[self::FLASH_KEY]);
			$this->redirect(301, 'this');
		}
		
		if(!$this->getHttpRequest()->isPost()) {
			$environmentSection = $this->context->session->getSection('environment');
			$environmentSection->previousLink = $backlink;
		}
	}

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

	public function flashMessage($message, $type = 'warning') {
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

	public function translate($phrase, $node = NULL, $count = NULL, array $variables = NULL) {
		return $this->context->translator->translate($phrase, $node, $count, $variables);
	}

	public function getEnvironment() {
		return $this->getService('environment');
	}

	protected function createComponentHeader() {
		list($modul, $presenter) = explode(':', $this->name, 2);

		$wlSets = $this->context->parameters['webloader']['sets'];

		$wlSet = NULL;
		if(isset($wlSets[$this->name])) {
			$wlSet = $wlSets[$this->name];
		} else if(isset($wlSets[$modul])) {
			$wlSet = $wlSets[$modul];
		}

		$cssFiles = array();
		$jsFiles = array();
		if(is_array($wlSet)) {
			foreach ($wlSet as $key => $value) {
				if(isset($value['css'])) {
					if(is_array($value['css'])) {
						$cssFiles = array_merge($cssFiles, $value['css']);
					} else {
						$cssFiles[] = $value['css'];
					}
				}
				if(isset($value['js'])) {
					if(is_array($value['js'])) {
						$jsFiles = array_merge($jsFiles, $value['js']);
					} else {
						$jsFiles[] = $value['js'];
					}
				}
			}
		}
		$this->cssFiles = $cssFiles;
		$this->jsFiles = $jsFiles;		

		$header = new HeaderControl;


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
		$files = new \WebLoader\FileCollection(WWW_DIR . '/packages');
		$files->addFiles($this->cssFiles);

		$compiler = \WebLoader\Compiler::createCssCompiler($files, WWW_DIR . '/webtemp');
		$compiler->addFileFilter(new \Webloader\Filter\LessFilter());

		return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
	}

	public function createComponentJs() {
		$files = new \WebLoader\FileCollection(WWW_DIR . '/packages');
		$files->addFiles($this->jsFiles);

		$compiler = \WebLoader\Compiler::createJsCompiler($files, WWW_DIR . '/webtemp');
		$compiler->setJoinFiles(FALSE);

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
