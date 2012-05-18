<?php

namespace Security;

use Nette\Caching\Cache,
	Nette\Utils\Finder,
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
		// debug(func_get_args());

		$data = $this->getData();
		debug($data);
		# roles
		foreach ($data['roles'] as $role) {
			call_user_func_array(array($this, 'addRole'), (array) $role);
		}

		# resources
		foreach ($data['resources'] as $resource) {
			call_user_func_array(array($this, 'addResource'),(array) $resource);
		}

		# rules
		foreach ($data['rules'] as $type => $ruleSet) {
			if(!is_array($ruleSet)) continue;
			foreach ($ruleSet as $rule) {
				call_user_func_array(array($this, $type), $rule);
			}
		}
	}

	public function getData() {
		if(isset($this->cache)) {
			if(!$data = $this->cache->load('data')) {
				$data = $this->buildData();
				$cache->save('data', $data);
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
		$data['roles'] = \Service\User\RoleList::getPairs('id', 'slug');
		foreach ($files as $filepath => $file) {
			$resource = $file->getBasename('.neon');
			$data['resources'][] = $resource;
			$content = $this->getConfigFromFile($filepath);
			debug($content);
			foreach ($content as $action => $permission) {
				foreach ($data['roles'] as $role) {
					$permissionType = $permission[$role];
					if($permissionType == self::DENY) continue;
					if($permissionType != self::ALLOW) {
						$assertion = $this->config['assertions'][$permissionType]['callback'];
						$permissionType = self::ALLOW;
					} else {
						$assertion = NULL;
					}
					$data['rules'][] = array(TRUE, $permissionType, $role, $resource, $action, $assertion);
				}
			}
		}
		// @todo porusuje to DI!
		debug($data);
	}

	public function getConfigFromFile($filename) {
		$config = new \Nette\Config\Loader;
		return $config->load($filename);
	}
}