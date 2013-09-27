<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/27/13 7:55 AM
 */

namespace Tralandia\Location;


use Environment\Collator;
use Environment\Environment;
use Nette;
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Routers\BaseRoute;
use Tralandia\BaseDao;

class Countries {


	/**
	 * @var \Tralandia\BaseDao
	 */
	private $locationDao;

	/**
	 * @var \Nette\Localization\ITranslator
	 */
	private $translator;

	/**
	 * @var \Environment\Collator
	 */
	private $collator;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	/**
	 * @param BaseDao $locationDao
	 * @param \Environment\Environment $environment
	 */
	public function __construct(BaseDao $locationDao, Environment $environment)
	{
		$this->locationDao = $locationDao;
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
	}


	/**
	 * @return \Entity\Location\Location[]
	 */
	public function findAll()
	{
		$qb = $this->locationDao->createQueryBuilder('l');

		$qb->innerJoin('l.type', 't');

		$qb->where($qb->expr()->eq('t.slug', ':type'))->setParameter('type', 'country');

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param Presenter $presenter
	 * @param string $destination
	 *
	 * @return array
	 */
	public function getForSelect(Presenter $presenter = NULL, $destination = 'this')
	{
		$countries = $this->findAll();

		$return = [];
		$sort = [];
		$elTemplate = Html::el('option');
		foreach($countries as $row) {
			/** @var $row \Entity\Location\Location */

			$parent = $row->getParent();
			$prefix = NULL;
			if($parent && $parent->getIso()) {
				$prefix = $this->translator->translate($parent->getName());
			}

			$text = ($prefix ? $prefix . ' - ' : '') . $this->translator->translate($row->getName());

			$key = $row->getId();
			if($presenter) {
				$link = $presenter->link($destination, [BaseRoute::PRIMARY_LOCATION => $row]);
				$el = clone $elTemplate;
				$return[$key] = $text;
				$sort[$key] = $el->value($key)->addAttributes(['data-redirect' => $link])->setText($text);
			} else {
				$return[$key] = $text;
			}
		}


		if($presenter) {
			$this->collator->asort($return);
			foreach($return as $key => $value) {
				$return[$key] = $sort[$key];
			}
		} else {
			$this->collator->asort($return);
		}

		return $return;

	}

}
