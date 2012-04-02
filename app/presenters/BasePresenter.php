<?php

use Nette\Application\UI\Presenter,
	Nette\Environment;

abstract class BasePresenter extends Presenter {

	public function beforeRender() {
		$this->template->setTranslator($this->getService('translator'));
		$this->template->registerHelper('image', callback('Tools::helperImage'));
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

	protected function createComponentHeader() {
		$header = new HeaderControl;

		list($modul, $presenter) = explode(':', $this->name, 2);

		$header->setDocType(HeaderControl::HTML_5);
		$header->setLanguage(HeaderControl::SLOVAK);
		
		$header->setTitle('Tralandia');

		// facebook xml namespace
		//$header->htmlTag->attrs['xmlns:fb'] = 'http://www.facebook.com/2008/fbml';

		$header->setTitleSeparator(' | ')
				->setTitlesReverseOrder(true)
				->setFavicon('favicon.ico')
				->addKeywords(array('reštaurácia', 'pizza', 'pizzeria', 'obedovať', 'obedové menu', 'obed'))
				->setDescription('Obedové menu vo vašich oblúbených reštauráciách a pizzeriách')
				->setRobots('index,follow')
				->setAuthor('David Durika');

		//CssLoader
		$css = $header['css'];
		$css->sourcePath = WWW_DIR . '/styles';
		$css->tempPath = WWW_DIR . '/webtemp';
		$css->tempUri = '/webtemp';	


		//JavascriptLoader
		$js = $header['js'];
		$js->sourcePath = WWW_DIR . '/scripts';
		//$js->joinFiles = FALSE; //Environment::isProduction();
		$js->tempPath = WWW_DIR . '/webtemp';
		$js->tempUri = '/webtemp';

		$styles = array();
		$scripts = array();
		if($modul == 'Front') {
		} else {
			$styles[] = 'main.css';
			
			$styles[] = 'syntaxhighlighter/shCore.css';
			$styles[] = 'syntaxhighlighter/shCoreDefault.css';
			$styles[] = 'syntaxhighlighter/shThemeDefault.css';



			$scripts[] = 'jquery.js';
			$scripts[] = 'jquery/nette.js';
			$scripts[] = 'jquery/livequery.js';
			$scripts[] = 'jquery/ui.js';
			$scripts[] = 'main.js';

			$scripts[] = 'syntaxhighlighter/shCore.js';
			$scripts[] = 'syntaxhighlighter/shLegacy.js';
			$scripts[] = 'syntaxhighlighter/shAutoloader.js';
			$scripts[] = 'syntaxhighlighter/shBrushPhp.js';
		}

		$css->addFiles($styles);
		$js->addFiles($scripts);

		
		if($modul != 'Admin' && Environment::isProduction()) {
			//$js->addFile('ga.js');
		}

		return $header;
	}


}
