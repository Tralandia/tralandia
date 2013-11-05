<?php

namespace Service\Location;

use Service, Doctrine, Entity;
use Nette\Utils\Strings;

/**
 * @author Dávid Ďurika
 */
class LocationService extends Service\BaseService {

	/**
	 * @var \Transliterator
	 */
	public $transliterator;

	protected $routingPathSegmentDao;
	protected $routingPathSegmentOldDao;
	protected $locationDao;

	protected $polygonService;

	public function injectBaseRepositories(\Nette\DI\Container $dic) {
		$this->routingPathSegmentDao = $dic->getService('doctrine.default.entityManager')->getDao(PATH_SEGMENT_ENTITY);
		$this->routingPathSegmentOldDao = $dic->getService('doctrine.default.entityManager')->getDao(PATH_SEGMENT_OLD_ENTITY);
		$this->locationDao = $dic->getService('doctrine.default.entityManager')->getDao(LOCATION_ENTITY);
	}


	public function inject(\Service\PolygonService $service, \Transliterator $transliterator) {
		$this->polygonService = $service;
		$this->transliterator = $transliterator;
	}

	public function setName(\Entity\Phrase\Phrase $name) {
		$this->getEntity()->name = $name;
		$this->updateSlug();
		$this->updateLocalName();
	}

	public function updateLocalName()
	{
		$namePhrase = $this->getEntity()->name;

		$translation = $namePhrase->getSourceTranslation();
		if(!$translation) {
			throw new \Exception('Nenasiel som zdrojovy preklad pre frazu!');
		}

		$this->getEntity()->localName = $translation->translation;
	}

	public function updateSlug() {
		$namePhrase = $this->getEntity()->name;

		$translation = $namePhrase->getSourceTranslation();
		if(!$translation) {
			throw new \Exception('Nenasiel som zdrojovy preklad pre frazu!');
		}
		$newSlug = $this->transliterator->transliterate($translation->translation);
		$newSlug = Strings::webalize($newSlug);

		$oldSlug = $this->getEntity()->slug;

		if ($newSlug != $oldSlug) {
			$existingLocation = 1;
			while ($existingLocation) {
				$existingLocation = $this->locationDao->findOneBy(array(
					'parent' => $this->getEntity()->parent,
					'slug' => $newSlug,
				));
				if ($existingLocation) {
					$newSlug .= 1;
				}
			}
			// Set the slug attribute for the location
			$this->getEntity()->slug = $newSlug;

			// Load the existing pathSegment and update it
			$pathSegment = $this->routingPathSegmentDao->findOneBy(array(
				'type' => 6,
				'entityId' => $this->getEntity()->id,
			));

			if ($pathSegment) {
				$pathSegment->pathSegment = $newSlug;
			} else {
				$pathSegment = $this->routingPathSegmentDao->createNew();
				$pathSegment->primaryLocation = $this->getEntity()->parent;
				$pathSegment->type = \Entity\Routing\PathSegment::LOCATION;
				$pathSegment->entityId = $this->getEntity()->id;
				$pathSegment->pathSegment = $newSlug;
			}

			$this->routingPathSegmentDao->save($pathSegment);

			if ($oldSlug) {
				// Create a new pathSegmentOld and update it
				$pathSegmentOld = $this->routingPathSegmentOldDao->createNew();
				$pathSegmentOld->primaryLocation = $pathSegment->primaryLocation;
				$pathSegmentOld->type = $pathSegment->type;
				$pathSegmentOld->entityId = $pathSegment->entityId;

				$pathSegmentOld->pathSegment = $oldSlug;
				$pathSegmentOld->pathSegmentNew = $pathSegment;

				$this->routingPathSegmentOldDao->save($pathSegmentOld);
			}
		}
		return $newSlug;

	}

	public function setLongitude(\Extras\Types\Latlong $longitude) {
		$this->getEntity()->longitude = $longitude;
		$this->polygonService->setRentalsForLocation($this->getEntity());
	}

	/**
	 * @deprecated
	 */
	public function getParent($slug = NULL)
	{
		throw \Exception('This method is deprecated. Use $locationEntity::getParent');
	}

}
