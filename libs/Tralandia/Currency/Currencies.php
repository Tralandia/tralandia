<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 24/03/14 13:49
 */

namespace Tralandia\Currency;


use Environment\Environment;
use Nette;
use Tralandia\BaseDao;

class Currencies
{

	/**
	 * @var \Tralandia\BaseDao
	 */
	private $currencyDao;

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

	public function __construct(BaseDao $currencyDao, Environment $environment)
	{
		$this->currencyDao = $currencyDao;
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
	}


	/**
	 * @return array
	 */
	public function getForSelect()
	{
		$return = [];
		$rows = $this->currencyDao->findAll();
		foreach($rows as $row) {
			$return[$row->id] = $row->iso . ' - ' . $this->translator->translate($row->name);
		}
		$this->collator->asort($return);

		return $return;

	}


}
