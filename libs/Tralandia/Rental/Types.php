<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 10/4/13 9:37 AM
 */

namespace Tralandia\Rental;


use Environment\Environment;
use Nette;
use Tralandia\BaseDao;

class Types
{

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

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $typeDao;


	/**
	 * @param \Tralandia\BaseDao $typeDao
	 * @param Environment $environment
	 */
	public function __construct(BaseDao $typeDao, Environment $environment)
	{
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
		$this->typeDao = $typeDao;
	}


	/**
	 * @return array
	 */
	public function getForSelect()
	{
		$rows = $this->typeDao->findAll();

		$return = \Tools::entitiesMap($rows, 'id', 'name', $this->translator);
		$this->collator->asort($return);

		foreach($rows as $row) {
			$return[$row->id] = [
				'label' => $return[$row->id],
				'entity' => $row,
			];
		}

		return $return;
	}

}
