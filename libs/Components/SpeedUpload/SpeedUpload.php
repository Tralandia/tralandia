<?php

use Nette\Application\UI\Control,
	Nette\Environment,
	Nette\Diagnostics\Debugger;

class SpeedUpload extends Control {

	const SALT = 'ef5c6817ded73ab054b7407c8aa22b0c0bff8ca0';
	const WEB_TEMP = '/webtemp';
	const CACHE_NAMESPACE = 'Nette.SpeedUpload';
	const TICKET = 'NetteSpeedUploadTicket';
	const FILE_NAME = 'Filedata';

	private $cache;

	private $randomName;

	public static $debug = false;

	private static $isTicket = false;

	public $onSuccess = array();

	public $onError = array();

	public $multi = true;

	protected $files = array(
		'uploadifyJs' => '/files/jquery.uploadify.min.js',
		'uploadifyCancel' => '/files/uploadify-cancel.png',
		'uploadifyCss' => '/files/uploadify.css',
		'uploadifySwf' => '/files/uploadify.swf'
	);

	protected $publishFiles = array();

	public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null) {
		parent::__construct($parent, $name);

		list($usec, $sec) = explode(' ', microtime());
		$this->randomName = md5($usec + $sec);
		$this->cache = Environment::getCache(self::CACHE_NAMESPACE);
		$this->parent->invalidateControl();
	}

	public function render() {
		$this->publishFiles();

		$this->template->publish = (object)$this->publishFiles;
		$this->template->ticketKeyName = self::TICKET;
		$this->template->filename = self::FILE_NAME;
		$this->template->multi = $this->multi;
		$this->template->randomName = substr($this->randomName, 0, 10);
		$this->template->setTranslator(Environment::getService('translator'));
		$this->template->setFile(dirname(__FILE__) . "/upload.latte");

		return $this->template->render();
	}

	public function handleTicket() {
		$hash = $this->generateHash();

		$this->cache->save($hash, array(
			'identity' => Environment::getUser()->getIdentity(),
			'created_at' => new \Nette\DateTime
		), array(
		    'expire' => time() + 600
		));
		self::log('TicketOut=' . $hash);
		die($hash);
	}

	public function handleUpload() {
		$this->invalidateControl();
		$this->onSuccess(new \Nette\Http\FileUpload($_FILES[self::FILE_NAME]));
	}

	private function publishFiles() {
		$webTemp = Environment::getConfig('wwwDir') . self::WEB_TEMP;

		if (!is_dir($webTemp))
			throw new \Nette\DirectoryNotFoundException("Directory '$webTemp' not exists.");
		if (!is_writable($webTemp))
			throw new \Nette\IOException("Directory '$webTemp' is not writable.");

		foreach ($this->files as $alias => $file) {
			$originFile = dirname(__FILE__) . '/' . $file;
			$file = md5(filemtime($originFile) . self::SALT) . '-' . basename($originFile);

			if (!file_exists("$webTemp/$file"))
				copy($originFile, "$webTemp/$file");

			$this->publishFiles[$alias] = $this->template->basePath . self::WEB_TEMP . '/' . $file;
		}
	}

	private function generateHash() {
		list($usec, $sec) = explode(' ', microtime());
		return md5(((float)$sec + (float)$usec) . rand() . self::SALT);
	}

	private static function log($message, $type = 'info') {
		if (self::$debug == true)
			Debugger::log($message, $type);
	}

	public static function catchTicket() {
		self::$isTicket = false;
		self::log('CatchTicket');

		// ticket prichadza len v poste
		if (Environment::getHttpRequest()->isPost() && Environment::getHttpRequest()->getPost(self::TICKET)) {
			$ticket = trim(Environment::getHttpRequest()->getPost(self::TICKET));
			if (empty($ticket)) {
				self::log('Ticket is empty');
				exit;
			}
			self::log('TicketIn=' . $ticket);

			if (preg_match('/^[a-f0-9]{32}$/', $ticket)) {
				$cache = Environment::getCache(self::CACHE_NAMESPACE);
				self::log("Ticket ($ticket) is OK.");
				if (!isset($cache[$ticket])) {
					// ticket nebol najdeny
					self::log("Ticket ($ticket) was not found.");
					exit;
				}
				self::log("Ticket ($ticket) was found (cool :).");

				// skusim najst uzivatela a ziskat jeho udaje
				$identity = $cache[$ticket]['identity'];
				try {
					$user = Environment::getUser();
					// vytvorim umely atentifikator
					$user->setAuthenticator(new \SpeedUploadAuthenticator(array(
					    $identity->id => $ticket
					), $identity));
					// prihlasim uzivatela podla tiketu
					$user->login($identity->id, $ticket);
					unset($cache[$ticket]);
					self::$isTicket = true;

					self::log("Is user logged in? " . ($user->isLoggedIn() ? "yes" : "no"));
					self::log("SpeedUpload login user with ID:" . $user->getIdentity()->id);
				} catch (\Nette\Security\AuthenticationException $e) {
					self::log("Ticket AuthenticationException: " . $e->getMessage());
					exit;
				}
			} else {
				self::log("Ticket ($ticket) is BAD.");
			}
		}
	}

	public static function isCatchedTicket() {
		return self::$isTicket;
	}
}
