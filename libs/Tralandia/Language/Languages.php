<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 9/26/13 1:18 PM
 */

namespace Tralandia\Language;


use Entity\Language;
use Environment\Environment;
use Nette;
use Nette\Localization\ITranslator;
use Tralandia\BaseDao;

class Languages {


	/**
	 * @var \Tralandia\BaseDao
	 */
	private $languageDao;

	/**
	 * @var \Environment\Environment
	 */
	private $environment;


	public function __construct(BaseDao $languageDao, Environment $environment)
	{
		$this->languageDao = $languageDao;
		$this->environment = $environment;
	}


	/**
	 * @param Language $sortFor
	 *
	 * @return \Entity\Language[]
	 */
	public function findLive(Language $sortFor = NULL)
	{
		$qb = $this->languageDao->createQueryBuilder('l');

		$qb->andWhere('l.live = ?1')->setParameter(1, Language::LIVE);

		if($sortFor) {
			$qb->innerJoin('l.name', 'p');
			$qb->innerJoin('p.translations', 't');
			$qb->andWhere('t.language = :language')->setParameter('language', $sortFor->getId());
			$qb->orderBy('t.translation');

		}

		return $qb->getQuery()->getResult();
	}


	/**
	 * @return null|\Entity\Language
	 */
	public function findCentral()
	{
		return $this->languageDao->find(CENTRAL_LANGUAGE);
	}
}
