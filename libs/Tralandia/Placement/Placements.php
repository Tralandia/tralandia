<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/3/13 10:43 AM
 */

namespace Tralandia\Placement;


use Environment\Environment;
use Nette;
use Tralandia\BaseDao;

class Placements
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $placementDao;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;

	/**
	 * @var \Tralandia\Localization\Translator
	 */
	private $translator;

	/**
	 * @var \Environment\Collator
	 */
	private $collator;


	public function __construct(BaseDao $placementDao, Environment $environment)
	{
		$this->placementDao = $placementDao;
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
	}


	/**
	 * @return array
	 */
	public function getForSelect()
	{
		$rows = $this->placementDao->findAll();

		$return = \Tools::entitiesMap($rows, 'id', 'name', $this->translator);
		$this->collator->asort($return);

		return $return;
	}
}
