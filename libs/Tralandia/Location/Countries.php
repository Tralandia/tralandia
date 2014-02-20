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
	 * @param int $limit
	 * @param int $minRentalCount
	 *
	 * @return \Entity\Location\Location[]
	 */
	public function findTop($minRentalCount = 100)
	{
		$qb = $this->locationDao->createQueryBuilder('l');

		$qb->innerJoin('l.type', 't')
			->where($qb->expr()->eq('t.slug', ':type'))->setParameter('type', 'country')
			->andWhere($qb->expr()->gte('l.rentalCount', ':minRentalCount'))->setParameter('minRentalCount', $minRentalCount)
			->orderBy('l.rentalCount', 'DESC');

		$result = $qb->getQuery()->getResult();

		$return = array();
		foreach ($result as $key => $value) {
			$return[$this->translator->translate($value->getName())] = $value;
		}

		$this->collator->ksort($return);

		return $return;
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


	/**
	 * @return array
	 */
	public function getPhonePrefixes() {

		$qb = $this->locationDao->createQueryBuilder('l');

		$qb->select('l.id, l.iso, l.phonePrefix')
			->innerJoin('l.type', 't')
			->where($qb->expr()->eq('t.slug', ':country'))
			->setParameter('country', 'country')
			->groupBy('l.phonePrefix')
			->orderBy('l.iso')
		;

		$return = [];
		$rows = $qb->getQuery()->getResult();

		foreach($rows as $row) {
			if ($row['phonePrefix'] == 1) {
				$return[$row['phonePrefix']] =  'US (+'.$row['phonePrefix'].')';
			} else if ($row['phonePrefix'] == 61) {
				$return[$row['phonePrefix']] = 'AU (+'.$row['phonePrefix'].')';
			} else {
				$return[$row['phonePrefix']] = strtoupper($row['iso']) . ' (+'.$row['phonePrefix'].')';
			}
		}

		$this->collator->asort($return);

		return $return;

	}


	/**
	 * @param $slug
	 *
	 * @return |Entity\Location\Location|NULL|mixed
	 */
	public function findOneBySlug($slug)
	{
		$qb = $this->locationDao->createQueryBuilder('e');

		$qb->innerJoin('e.type', 't')
			->where($qb->expr()->eq('t.slug', ':type'))
			->setParameter('type', 'country');

		$qb->andWhere($qb->expr()->eq('e.slug', ':slug'))->setParameter('slug', $slug);

		return $qb->getQuery()->getOneOrNullResult();
	}

}
