<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Service\BaseService {
	
	protected $environment;
	protected $page;

	public function __construct(Extras\Environment $environment, $pageRepositoryAccessor)
	{
		$this->environment = $environment;
		$this->setPage($pageRepositoryAccessor);
	}


	protected function setPage($pageRepositoryAccessor)
	{
		$request = $this->environment->getRequest();
		$params = $request->getParameters();
		$destination = $request->name . ':' . $params['action'];
		if($params['page']) {

		} else {
			$pathSegmentTypes = \Routers\FrontRoute::$pathSegmentTypes;
			$hash = array();
			foreach ($pathSegmentTypes as $name => $value) {
				if(isset($params[$name])) {
					if($name == 'tag') {
						#@todo dorobit to ze ci je to tagAfter alebo tagBefore
						$name = 'tagAfter';
					}
					$hash[] = $name;
				}
			}
			$hash = '/'.implode('/', $hash);
			$page = $pageRepositoryAccessor->get()->findOneBy(array('destination' => $destination, 'hash' => $hash));
		}

		$this->page = $page;
	}


}