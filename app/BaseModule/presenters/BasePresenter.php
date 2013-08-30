<?php

use Entity\User\User;
use Nette\Security\IAuthorizator;
use Nette\Utils\Arrays;
use Nette\Utils\Finder;
use Nette\Utils\Strings;
use Nette\Application\UI\Presenter;
use Routers\FrontRoute;
use Routers\OwnerRouteList;


abstract class BasePresenter extends Presenter {

	use \Kdyby\AutowireProperties;
	use \TFindEntityHelper;

	const FLASH_INFO = 'info';
	const FLASH_WARNING = 'warning';
	const FLASH_SUCCESS = 'success';
	const FLASH_ERROR = 'error';

	/**
	 * @persistent
	 * @var \Entity\Language
	 */
	public $language;

	/**
	 * @persistent
	 * @var \Entity\Location\Location
	 */
	public $primaryLocation;

	/**
	 * @var \Entity\Page
	 */
	public $page;

	public $userRepositoryAccessor;


	public $cssFiles;
	public $cssRemoteFiles;
	public $jsFiles;
	public $jsRemoteFiles;

	/**
	 * @autowire
	 * @var \BaseModule\Forms\ISimpleFormFactory
	 */
	protected $simpleFormFactory;

	/**
	 * @autowire
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @autowire
	 * @var \Security\Authenticator
	 */
	protected $authenticator;

	/**
	 * @autowire
	 * @var \Image\RentalImagePipe
	 */
	protected $rentalImagePipe;

	/**
	 * @autowire
	 * @var \Service\Contact\AddressNormalizer
	 */
	protected $addressNormalizer;

	/**
	 * @var \Repository\LanguageRepository
	 */
	protected $languageRepositoryAccessor;

	/**
	 * @var \Repository\Location\LocationRepository
	 */
	protected $locationRepositoryAccessor;

	/**
	 * @autowire
	 * @var \Device
	 */
	protected $device;

	/**
	 * @autowire
	 * @var \TranslationTexy
	 */
	protected $texy;

	/**
	 * @autowire
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var User
	 */
	public $loggedUser;

	/**
	 * @autowire
	 * @var \Tester\ITester
	 */
	public $tester;


	public function injectLLRepositories(\Nette\DI\Container $dic) {
		$this->languageRepositoryAccessor = $dic->languageRepositoryAccessor;
		$this->locationRepositoryAccessor = $dic->locationRepositoryAccessor;
	}


	protected function startup() {
		parent::startup();
		$this->initializeFindEntityHelper($this->em);
		// odstranuje neplatne _fid s url
		if (!$this->hasFlashSession() && !empty($this->params[self::FLASH_KEY])) {
			unset($this->params[self::FLASH_KEY]);
			$this->redirect(301, 'this');
		}

		if($device = $this->getParameter(FrontRoute::DEVICE)) {
			if(in_array($device, [Device::MOBILE, Device::DESKTOP])) {
				$this->device->setDevice($device);
			}
			$parameters = $this->getParameters();
			unset($parameters[FrontRoute::DEVICE], $parameters['primaryLocation'], $parameters['language']);
			$this->redirect('this', $parameters);
		}

		if($autologin = $this->getParameter(OwnerRouteList::AUTOLOGIN)) {
			try{
				$identity = $this->authenticator->autologin($autologin);
				$this->login($identity);
			} catch(\Nette\Security\AuthenticationException $e) {
			}
			$parameters = $this->getParameters();
			unset($parameters[OwnerRouteList::AUTOLOGIN], $parameters['primaryLocation'], $parameters['language']);
			$this->redirect('this', $parameters);
		}

		$backLink = $this->storeRequest();
		if(!$this->getHttpRequest()->isPost()) {
			$environmentSection = $this->context->session->getSection('environment');
			$environmentSection->previousLink = $environmentSection->actualLink;
			$environmentSection->actualLink = $backLink;
		}

		if($this->user->isLoggedIn()) {
			$this->loggedUser = $this->userRepositoryAccessor->get()->find($this->user->getId());
			if(!$this->loggedUser && !$this->isLinkCurrent(':Front:Sign:out')) {
				$this->redirect(':Front:Sign:out');
			}
		}
	}

	public function injectUserRepository(\Nette\DI\Container $dic) {
		$this->userRepositoryAccessor = $dic->userRepositoryAccessor;
	}

	public function getPreviousBackLink() {
		$environmentSection = $this->context->session->getSection('environment');
		return $environmentSection->previousLink;
	}

	public function checkPermission($resource = IAuthorizator::ALL, $privilege = IAuthorizator::ALL)
	{
		if (!$this->getUser()->isAllowed($resource, $privilege)) $this->accessDeny($resource, $privilege);
	}

	public function accessDeny($resource, $privilege)
	{
		$class = [];
		$class[] = is_scalar($resource) ? $resource : NULL;
		$class[] = is_scalar($privilege) ? $privilege : NULL;
		$class = implode(':', array_filter($class));

		$this->flashMessage('Hey dude! You don\'t have permissions to view that page. ' . $class, self::FLASH_WARNING);
		$this->redirect(':Front:Home:default');
	}

	public function beforeRender() {
		parent::beforeRender();

		$parameters = $this->getContext()->getParameters();
		$this->template->projectEmail = $parameters['projectEmail'];
		$this->template->staticPath = '/'; #@todo toto tu je na co ?
		$this->template->setTranslator($this->getService('translator'));
		$this->template->registerHelper('image', callback('Tools::helperImage'));
		$this->template->loggedUser = $this->loggedUser;
		$this->template->isMobile = $this->device->isMobile();
		$this->template->rand = rand(1, 1000);
		$this->template->isWorld = $this->primaryLocation->isWorld();

		$this->template->gaCode = $parameters['googleAnalytics']['code'];

		$this->template->environmentPrimaryLocation = $this->primaryLocation;

		if($this->tester instanceof \Tester\Options) {
			$this->template->tester = $this->tester;
		}

		$this->fillTemplateWithCacheOptions($this->template);
	}

	public function fillTemplateWithCacheOptions($template)
	{
		$parameters = $this->getContext()->getParameters();

		$language = 'language/' . $this->environment->getLanguage()->getIso();
		$primaryLocation = 'primaryLocation/' . $this->environment->getPrimaryLocation()->getIso();
		$userLoggedIn = $this->getUser()->isLoggedIn();
		$urlWithGet = count($this->getHttpRequest()->getQuery()) > 0;

		$templateCache = $parameters['templateCache'];
		$templateCacheEnabled = $templateCache['enabled'];
		unset($templateCache['enabled']);
		$url = $this->getHttpRequest()->getUrl();
		$url = $url->getHostUrl() . $url->getPath();
		$zeroSearchValues = ['Front:Home', 'Front:AboutUs', 'Front:SupportUs', 'Front:Faq', 'Front:Tou', 'Front:Sign', 'Front:Registration'];
		$zeroSearch = in_array($this->getName(), $zeroSearchValues) ? 'zeroSearch' : 'search';
		$homepage = $this->getName() == 'Front:Home' ? 'homepage' : 'notHomepage';
		$isHomePage = $this->getName() == 'Front:Home';
		foreach($templateCache as $optionName => $options) {
			$searchVariables = ['[name]', '[language]', '[primaryLocation]', '[url]', '[homepage]', '[zeroSearch]'];
			$replaceVariables = [$optionName, $language, $primaryLocation, $url, $homepage, $zeroSearch];

			$propertyName = $optionName . 'CacheOptions';

			$options['enabled'] = $templateCacheEnabled && $options['enabled'] ? 1 : 0;

			if(array_key_exists('if', $options)) {
				$options['if'] = str_replace(
					['[!userLoggedIn]', '[!urlWithGet]', '[homepage]', '[zeroSearch]'],
					[$userLoggedIn ? 0 : 1, $urlWithGet ? 0 : 1, $isHomePage ? 1 : 0, $zeroSearch == 'zeroSearch' ? 1 : 0],
					$options['if']
				);
				$options['enabled'] .= ' && (' . $options['if'] . ')';
				unset($options['if']);
			}

			$options['key'] = str_replace($searchVariables, $replaceVariables, $options['key']);

			$options['tags'][] = $options['key'];
			if(is_array($options['tags'])) {
				foreach($options['tags'] as $keyTag => $tag) {
					$options['tags'][$keyTag] = str_replace($searchVariables, $replaceVariables, $tag);
				}
			}

			eval("\$options['enabled'] = {$options['enabled']};");

			$template->{$propertyName} = $options;
			//d($optionName, $options);
		}

	}

	protected function createTemplate($class = NULL) {
		/** @var \Nette\Templating\FileTemplate|\stdClass $template */
		$template = parent::createTemplate($class);
		$helpers = $this->getContext()->getService('templateHelpers');
		$template->registerHelperLoader(array($helpers, 'loader'));
		$template->_imagePipe = $this->rentalImagePipe;

		$template->netteCacheStorage = $this->getContext()->templateCacheStorage;

		return $template;
	}


	protected function createComponentFlashes($name) {
		return new FlashesControl($this, $name);
	}

	public function flashMessage($message, $type = self::FLASH_WARNING) {
		if(is_string($message)) {
			$message = $this->translate($message);
		} else if(is_array($message)) {
			$message = call_user_func_array([$this, 'translate'], $message);
		}
		$message = $this->texy->processLine($message);
		parent::flashMessage($message, $type);
		$this->invalidateFlashMessage();
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

	public function translate()
	{
		$args = func_get_args();
		return call_user_func_array([$this->context->translator, 'translate'], $args);
	}

	/**
	 * @return HeaderControl
	 */
	protected function createComponentHead() {
		list($module, $presenter) = explode(':', $this->name, 2);
		$action = $this->action;

		$wlSets = $this->context->parameters['webloader']['sets'];

		$wlSet = NULL;

		if(isset($wlSets[$this->name.':'.$action])) {
			$wlSet = $wlSets[$this->name.':'.$action];
		} else if(isset($wlSets[$this->name])) {
			$wlSet = $wlSets[$this->name];
		} else if(isset($wlSets[$module])) {
			$wlSet = $wlSets[$module];
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
		$header->setLanguage($this->primaryLocation->getIso());

		$header->setTitle('Tralandia');

		// facebook xml namespace
		//$header->htmlTag->attrs['xmlns:fb'] = 'http://www.facebook.com/2008/fbml';

		$header->setTitleSeparator(' - ')
				->setTitlesReverseOrder(TRUE)
				->addKeywords(array())
				->setDescription('')
				->setRobots('index,follow')
				->setAuthor('Tralandia ltd.');


		if(count($this->getHttpRequest()->getQuery())) {
			$header->setRobots('noindex,follow');
		}


		return $header;
	}

	public function createComponentCss() {
		$parameters = $this->getContext()->getParameters();

		$files = new \WebLoader\FileCollection(WWW_DIR . '/packages');
		$files->addFiles($this->cssFiles);

		if($this->cssRemoteFiles) {
			$files->addRemoteFiles($this->cssRemoteFiles);
		}

		$compiler = \WebLoader\Compiler::createCssCompiler($files, WWW_DIR . $parameters['webTempDir']);
		$compiler->addFileFilter(new \Webloader\Filter\LessFilter());

//		$cssVariables = [
//			''
//		];
//		$compiler->addFileFilter(new \Webloader\Filter\VariablesFilter($cssVariables));

//		$a = $parameters['staticDomain'] ? $parameters['staticDomain'] : $this->getBaseUrl();
		$a = $this->getBaseUrl();

		return new \WebLoader\Nette\CssLoader($compiler, $a . $parameters['webTempPath']);
	}

	public function createComponentJs() {
		$parameters = $this->getContext()->getParameters();

		$files = new \WebLoader\FileCollection(WWW_DIR . '/packages');
		$files->addFiles($this->jsFiles);

		if($this->jsRemoteFiles) {
			$files->addRemoteFiles($this->jsRemoteFiles);
		}

		$compiler = \WebLoader\Compiler::createJsCompiler($files, WWW_DIR . $parameters['webTempDir']);
		$compiler->setJoinFiles(TRUE);

//		$a = $parameters['staticDomain'] ? $parameters['staticDomain'] : $this->getBaseUrl();
		$a = $this->getBaseUrl();

		return new \WebLoader\Nette\JavaScriptLoader($compiler, $a . $parameters['webTempPath']);
	}

	public function getBaseUrl() {
		return $this->template->baseUrl;
	}


	public function actionValidateAddress()
	{

		$json = [];
		$address = $this->getParameter('address');
		$locality = $this->getParameter('locality');
		$postalCode = $this->getParameter('postalCode');
		$primaryLocation = $this->getParameter('location');
		$latitude = $this->getParameter('latitude');
		$longitude = $this->getParameter('longitude');

		$addressNormalizer = $this->addressNormalizer;

		$info = [];
		if($latitude && $longitude) {
			$gps = new \Extras\Types\Latlong($latitude, $longitude);
			if($gps->isValid()) {
				$info = $addressNormalizer->getInfoUsingGps($gps);
			}
		} else {
			$primaryLocation = $this->locationRepositoryAccessor->get()->find($primaryLocation);
			$info = $addressNormalizer->getInfoUsingAddress($primaryLocation, $address, '', $locality, $postalCode);
		}



		$jsonElements = [
			'address' => [
				'value' => Arrays::get($info, 'address', NULL),
			],
			'locality' => [
				'value' => Arrays::get($info, 'locality', NULL),
			],
			'postalCode' => [
				'value' => Arrays::get($info, 'postalCode', NULL),
			],
			'location' => [
				'value' => array_key_exists('location', $info) ? $info['location']->getId() : NULL,
			],
			'latitude' => [
				'value' => Arrays::get($info, 'latitude', NULL),
			],
			'longitude' => [
				'value' => Arrays::get($info, 'longitude', NULL),
			],
		];

		$json = [
			'status' => TRUE,
			'elements' => $jsonElements
		];

		$this->sendJson($json);
	}

	public function actionRestoreOriginalIdentity()
	{
		/** @var $identity \Security\Identity */
		$identity = $this->getUser()->getIdentity();
		if($identity->isFake()) {
			/** @var $identity \Security\FakeIdentity */
			$originalIdentity = $identity->getOriginalIdentity();
			$this->login($originalIdentity);
			$this->actionAfterLogin();
		}
	}


	public function redirectToCorrectDomain($login, $password)
	{
		$identity = $this->authenticator->authenticate([$login, $password]);

		/** @var $user \Entity\User\User */
		$user = $this->userRepositoryAccessor->get()->find($identity->getId());

		if($this->primaryLocation->getId() != $user->getPrimaryLocation()->getId()
			|| $this->language->getId() != $user->getLanguage()->getId())
		{
			$hash = $this->authenticator->calculateAutoLoginHash($user);
			$parameters = [
				\Routers\BaseRoute::PRIMARY_LOCATION => $user->getPrimaryLocation(),
				\Routers\BaseRoute::LANGUAGE => $user->getLanguage(),
				OwnerRouteList::AUTOLOGIN => $hash,
				FrontRoute::PAGE => NULL,
			];
			$this->redirect(':Front:Sign:afterLogin', $parameters);
		}

		return $identity;
	}


	public function actionAfterLogin()
	{
		$user = $this->getUser();
		if ($homepage = $user->getIdentity()->homepage){
			$this->redirect($homepage);
		}
		$this->redirect('this');
	}


	public function login(\Security\Identity $identity)
	{
//		if($identity->isInRole(\Entity\User\Role::OWNER)) {
//			$this->flashMessage('o100194', self::FLASH_SUCCESS);
//			$this->redirect(':Front:Sign:in');
//		}
		$user = $this->getUser();
		$user->setExpiration('+ 30 days', FALSE);
		$user->login($identity);
	}


	public function actionSetTester($id)
	{
		if($this->tester instanceof \Tester\Options) {
			$this->tester->setEmail($id);
			$this->flashMessage('Tester set to: ' . $id);
		}
		$this->redirect(':Front:Home:');
	}

}
