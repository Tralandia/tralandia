<?php

use Nette\Environment;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use Nette\Security\User;
use Nette\Reflection\Property;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter {

	use \Kdyby\AutowireProperties;

	/** @persistent */
	public $language;

	/** @persistent */
	public $primaryLocation;


	public $cssFiles;
	public $cssRemoteFiles;
	public $jsFiles;
	public $jsRemoteFiles;

	/**
	 * @autowire
	 * @var \Security\Authenticator
	 */
	protected $authenticator;

	protected function startup() {
		parent::startup();
		// odstranuje neplatne _fid s url
		if (!$this->hasFlashSession() && !empty($this->params[self::FLASH_KEY])) {
			unset($this->params[self::FLASH_KEY]);
			$this->redirect(301, 'this');
		}

		//$this->initCallbackPanel();

		if($autologin = $this->getParameter(\Routers\OwnerRouteList::AUTOLOGIN)) {
			try{
				$identity = $this->authenticator->autologin($autologin);
				$this->getUser()->login($identity);
			} catch(\Nette\Security\AuthenticationException $e) {
			}
			$parameters = $this->getParameters();
			unset($parameters['l'], $parameters['primaryLocation'], $parameters['language']);
			$this->redirect('this', $parameters);
		}

		$backLink = $this->storeRequest();
		if(!$this->getHttpRequest()->isPost()) {
			$environmentSection = $this->context->session->getSection('environment');
			$environmentSection->previousLink = $environmentSection->actualLink;
			$environmentSection->actualLink = $backLink;
		}
	}

	public function initCallbackPanel()
	{
		d($this->link(':Admin:RunRobot:searchCache', array('language' => $this->language->getId(),
			'primaryLocation' => $this->primaryLocation->getId())));
		$self = $this;
		$callbacks = array();
		$callbacks['test'] = array(
			'name' => "test",
			'function' => function () use($self) {$self->redirect(':Admin:RunRobot:searchCache');},
		);

		\Addons\Panels\Callback::register($this->getContext(), $callbacks);
	}

	public function getPreviousBackLink() {
		$environmentSection = $this->context->session->getSection('environment');
		return $environmentSection->previousLink;
	}
	
	public function accessDeny()
	{
		$this->flashMessage('Hey dude! You don\'t have permissions to view that page.', 'warning');
		$this->redirect(':Front:Home:default');
	}

	public function beforeRender() {
		parent::beforeRender();
		$parameters = $this->getContext()->getParameters();
		$this->template->staticPath = '/';
		$this->template->setTranslator($this->getService('translator'));
		$this->template->registerHelper('image', callback('Tools::helperImage'));
		$this->template->useTemplateCache = $parameters['useTemplateCache'];
	}

	protected function createTemplate($class = NULL) {
		/** @var \Nette\Templating\FileTemplate|\stdClass $template */
		$template = parent::createTemplate($class);
		$helpers = $this->getContext()->getService('templateHelpers');
		$template->registerHelperLoader(array($helpers, 'loader'));
		return $template;
	}


	protected function createComponentFlashes($name) {
		return new FlashesControl($this, $name);
	}

	public function getParameters() {
		return $this->getParameter();
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

	/**
	 * @return HeaderControl
	 */
	protected function createComponentHeader() {
		list($modul, $presenter) = explode(':', $this->name, 2);
		$action = $this->action;

		$wlSets = $this->context->parameters['webloader']['sets'];

		$wlSet = NULL;

		if(isset($wlSets[$this->name.':'.$action])) {
			$wlSet = $wlSets[$this->name.':'.$action];
		} else if(isset($wlSets[$this->name])) {
			$wlSet = $wlSets[$this->name];
		} else if(isset($wlSets[$modul])) {
			$wlSet = $wlSets[$modul];
		}

		$cssFiles = array();
		$cssRemoteFiles = array();
		$jsFiles = array();
		$jsRemoteFiles = array();
		if(is_array($wlSet)) {
			foreach ($wlSet as $key => $value) {
				if(isset($value['css'])) {
					if(!is_array($value['css'])) {
						$value['css'] = array($value['css']);
					}
					foreach ($value['css'] as $filePath) {
						if(Strings::startsWith($filePath, 'http://') || Strings::startsWith($filePath, 'https://')) {
							$cssRemoteFiles[] = $filePath;
						} else {
							$cssFiles[] = $filePath;
						}
					}
				}

				if(isset($value['js'])) {
					if(!is_array($value['js'])) {
						$value['js'] = array($value['js']);
					}
					foreach ($value['js'] as $filePath) {
						if(Strings::startsWith($filePath, 'http://') || Strings::startsWith($filePath, 'https://')) {
							$jsRemoteFiles[] = $filePath;
						} else {
							$jsFiles[] = $filePath;
						}
					}
				}
			}
		}
		$this->cssFiles = array_unique($cssFiles);
		$this->cssRemoteFiles = array_unique($cssRemoteFiles);
		$this->jsFiles = array_unique($jsFiles);
		$this->jsRemoteFiles = array_unique($jsRemoteFiles);

		$header = new HeaderControl;


		$header->setDocType(HeaderControl::HTML_5);
		$header->setLanguage(HeaderControl::SLOVAK);
		
		$header->setTitle('Tralandia');

		// facebook xml namespace
		//$header->htmlTag->attrs['xmlns:fb'] = 'http://www.facebook.com/2008/fbml';

		$header->setTitleSeparator(' - ')
				->setTitlesReverseOrder(TRUE)
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

		if($this->cssRemoteFiles) {
			$files->addRemoteFile($this->cssRemoteFiles);
		}

		$compiler = \WebLoader\Compiler::createCssCompiler($files, WWW_DIR . '/webtemp');
		$compiler->addFileFilter(new \Webloader\Filter\LessFilter());

		return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
	}

	public function createComponentJs() {
		$files = new \WebLoader\FileCollection(WWW_DIR . '/packages');
		$files->addFiles($this->jsFiles);

		if($this->jsRemoteFiles) {
			$files->addRemoteFile($this->jsRemoteFiles);
		}

		$compiler = \WebLoader\Compiler::createJsCompiler($files, WWW_DIR . '/webtemp');
		$compiler->setJoinFiles(TRUE);

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
}
