<?php

namespace Service\Seo;

use Nette, Extras, Service, Doctrine, Entity;

/**
 * @author Dávid Ďurika
 */
class SeoService extends Service\BaseService {
	
	protected $request;
	protected $page;

	public function __construct(Nette\Application\Request $request, $pageRepositoryAccessor)
	{
		$this->request = $request;
		$this->setPage($pageRepositoryAccessor);
	}


	protected function setPage($pageRepositoryAccessor)
	{
		$request = $this->request;
		$params = $request->getParameters();
		$destination = ':Front:'.$request->getPresenterName() . ':' . $params['action'];
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