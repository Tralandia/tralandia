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
use Nette\Application\UI\Presenter;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Routers\BaseRoute;
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


	/**
	 * @var \Tralandia\Localization\Translator
	 */
	private $translator;


	/**
	 * @var \Environment\Collator
	 */
	private $collator;


	public function __construct(BaseDao $languageDao, Environment $environment)
	{
		$this->languageDao = $languageDao;
		$this->environment = $environment;
		$this->translator = $environment->getTranslator();
		$this->collator = $environment->getLocale()->getCollator();
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
	 * @param null $order
	 *
	 * @return \Entity\Language[]
	 */
	public function findSupported($order = NULL) {
		return $this->languageDao->findBySupported(Language::SUPPORTED, $order);
	}


	/**
	 * @return null|\Entity\Language
	 */
	public function findCentral()
	{
		return $this->languageDao->find(CENTRAL_LANGUAGE);
	}


	/**
	 * @return array
	 */
	public function getSupportedSortedByName() {
		$supported = $this->findSupported();

		$return = array();
		foreach ($supported as $key => $language) {
			$return[$this->translator->translate($language->getName())] = $language;
		}

		$this->collator->ksort($return);

		return $return;

	}


	/**
	 * @param $ids
	 *
	 * @return \Entity\Language[]
	 */
	public function findByIds($ids)
	{
		$qb = $this->languageDao->createQueryBuilder('e');
		$qb->where($qb->expr()->in('e.id', $ids));

		return $qb->getQuery()->getResult();
	}


	/**
	 * @param array $iso
	 *
	 * @return array
	 */
	public function findPairsByIso(array $iso)
	{
		$qb = $this->languageDao->createQueryBuilder('e');
		$qb->where($qb->expr()->in('e.iso', $iso));

		$result = $qb->getQuery()->getResult();
		$result = \Tools::arrayMap($result, function($key, $value) {return $value->getIso();}, NULL);

		return $result;
	}



	/**
	 * @param Presenter $presenter
	 *
	 * @return array
	 */
	public function getForSelectWithLinks(Presenter $presenter = NULL)
	{
		$rows = $this->findSupported();
		$return = [];
		$htmlOptions = [];
		$elTemplate = Html::el('option');
		foreach($rows as $row) {
			$key = $row->getId();
			$el = clone $elTemplate;

			$name = $this->translator->translate($row->getName());
			$localName = $row->getName()->hasTranslationText($row) ? $row->getName()->getTranslationText($row) : NULL;
			$text = (!$localName || $name == $localName) ? $name : $name . ' (' . Strings::lower($localName) . ')';
			$return[$key] = $text;

			if($presenter) {
				$link = $presenter->link('Registration:default', [BaseRoute::LANGUAGE => $row]);
				$htmlOptions[$key] = $el->value($key)->addAttributes(['data-redirect' => $link])->setText($text);
			}
		}


		if($presenter) {
			$this->collator->asort($return);
			foreach($return as $key => $value) {
				$return[$key] = $htmlOptions[$key];
			}
		} else {
			$this->collator->asort($return);
		}

		return $return;
	}


	/**
	 * @return array
	 */
	public function getSupportedForSelect()
	{
		$return = [];
		$rows = $this->findSupported();
		foreach($rows as $row) {
			$return[$row->id] = $this->translator->translate($row->name);
		}
		$this->collator->asort($return);

		return $return;

	}



}
