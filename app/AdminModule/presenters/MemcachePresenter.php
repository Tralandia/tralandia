<?php

namespace AdminModule;

use Nette\Application as NA,
	Nette\Environment,
	Nette\Diagnostics\Debugger,
	Nette\Utils\Html,
	Nette\Utils\Strings,
	Nette\Utils\Arrays,
	Service\Dictionary as D,
	Service as S,
	Service\Log as SLog;

class MemcachePresenter extends BasePresenter {

	public $session;

	protected $memcache;

	public function actionDefault() {
	    $this->memcache = new \Memcache;
	    $this->memcache->connect('localhost', 11211) or die ("Could not connect to memcache server");

	    $filter = 'tralandia_RentalOrderCache';
	    $filter = 'tralandia_RentalSearchCache';

		$keys = $this->getMemcacheKeys($filter);

		$this->template->keys = array();
		foreach ($keys as $key => $value) {
			$this->template->keys[] = array(
				'key' => str_replace($filter, '', $value),
				'content' => \Nette\Diagnostics\Dumper::toHtml($this->memcache->get($value)),
			);
		}
	}

	function getMemcacheKeys($filter = NULL) {

		$foundKeys = array();

	    $list = array();
	    $allSlabs = $this->memcache->getExtendedStats('slabs');
	    //$items = $this->memcache->getExtendedStats('items');
	    foreach($allSlabs as $server => $slabs) {
	        foreach($slabs AS $slabId => $slabMeta) {
	           $cdump = $this->memcache->getExtendedStats('cachedump',(int)$slabId);
	            foreach($cdump AS $keys => $arrVal) {
	                if (!is_array($arrVal)) continue;
	                foreach($arrVal AS $k => $v) {
	                	if ($filter && strpos($k, $filter) !== 0) {
	                		continue;
	                	}
	                    $foundKeys[] = $k;
	                }
	           }
	        }
	    }

	    return $foundKeys;
	}
}
