<?php

namespace Security;

use Nette\Caching\Cache,
	Nette\Utils\Finder,
	Nette\Utils\Strings,
	Nette\Security\Permission;

class Acl extends Permission {

	protected $cache;
	protected $config;

	public function setCache(Cache $cache) {
		$this->cache = $cache;
	}

	public function setConfig($config) {
		$this->config = $config;
	}

	public function setup() {
		$data = $this->getData();

		# roles
		foreach ($data['roles'] as $role) {
			call_user_func_array(array($this, 'addRole'), (array) $role);
		}

		# resources
		foreach ($data['resources'] as $resource) {
			call_user_func_array(array($this, 'addResource'),(array) $resource);
		}

		# rules
		foreach ($data['rules'] as $type => $rule) {
			call_user_func_array(array($this, 'setRule'),(array) $rule);
		}
	}

	public function getData() {
		if(isset($this->cache)) {
			if(!$data = $this->cache->load('data')) {
				$data = $this->buildData();
				$this->cache->save('data', $data, array(
					Cache::TAGS => 'acl'
				));
			}
		} else {
			$data = $this->buildData();
		}
		return $data;
	}

	public function buildData() {
		$presenters = iterator_to_array(Finder::findFiles('*.neon')->from($this->config['presentersDir'])->getIterator());
		$entities = iterator_to_array(Finder::findFiles('*.neon')->from($this->config['entitiesDir'])->getIterator());
		$files = array_merge($presenters, $entities);

		$data = array();
		// @todo porusuje to DI!
		$data['roles'] = \Service\User\RoleList::forAcl();
		foreach ($files as $filepath => $file) {
			//debug($file);
			$baseName = $file->getBasename('.neon');
			$baseName = Strings::endsWith($baseName, 'Presenter') ? substr($baseName, 0, -9) : $baseName;
			$resource = str_replace(array('_', 'Module-'), array('\\', ':'), $baseName);

			$data['resources'][] = $resource;
			$content = $this->getConfigFromFile($filepath);

			foreach ($content as $action => $permission) {
				foreach ($data['roles'] as $role) {
					if (!isset($permission[$role])) {
						continue;
					}
					$permissionType = $permission[$role];
					if($permissionType == 'deny') continue;
					if($permissionType != 'allow') {
						$assertion = $this->config['assertions'][$permissionType]['callback'];
					} else {
						$assertion = NULL;
					}
					$permissionType = self::ALLOW;
					$data['rules'][] = array(TRUE, $permissionType, $role, $resource, $action, $assertion);
				}
			}
		}
		$data['roles'][] = 'superadmin';
		$data['rules'][] = array(TRUE, self::ALLOW, 'superadmin', self::ALL, self::ALL);
		return $data;
	}

	public function getConfigFromFile($filename) {
		$config = new \Nette\Config\Loader;
		return $config->load($filename);
	}
}