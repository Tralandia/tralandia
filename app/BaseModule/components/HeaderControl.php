<?php 
namespace BaseModule\Components;

use Doctrine\ORM\EntityManager;
use Environment\Environment;
use Nette\DateTime;
use Service\Seo\ISeoServiceFactory;
use Service\Seo\SeoService;

class HeaderControl extends \BaseModule\Components\BaseControl {

	/**
	 * @var \Service\Seo\SeoService
	 */
	protected $pageSeo;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * @var \Environment\Environment
	 */
	protected $environment;

	/**
	 * @var \Service\Seo\ISeoServiceFactory
	 */
	protected $seoFactory;

	public function __construct(SeoService $pageSeo, Environment $environment, EntityManager $em,
								ISeoServiceFactory $seoFactory) {
		parent::__construct();
		$this->pageSeo = $pageSeo;
		$this->environment = $environment;
		$this->em = $em;
		$this->seoFactory = $seoFactory;
	}

	public function render() {

		$template = $this->template;

		$languageRepository = $this->em->getRepository('\Entity\Language');
		$supportedLanguages = $languageRepository->getSupportedSortedByName();

		$primaryLocation = $this->environment->getPrimaryLocation();

		$domain = $primaryLocation->getDomain()->getDomain();

		$template->slogan = $template->translate('o21083').' '.$template->translate(
			$primaryLocation->getName(),
			NULL,
			array('case' => \Entity\Language::LOCATIVE)
		);


		$template->localeCode = $this->environment->getLocale()->getCode();
		$template->localeGoogleCode = $this->environment->getLocale()->getGooglePlusLangCode();

		$template->homepageUrl = $this->presenter->link('//:Front:Home:');
		$template->homepageSeo = $this->seoFactory->create($template->homepageUrl, $this->presenter->getLastCreatedRequest());
		$template->domainHost = ucfirst(strstr($domain, '.', TRUE));
		$template->domainExtension = strstr($domain, '.');


		$template->supportedLanguages = array_chunk($supportedLanguages, round(count($supportedLanguages)/3));

		$template->environment = $this->environment;
		$template->pageSeo = $this->pageSeo;

		$template->render();
	}


}

interface IHeaderControlFactory {
	/**
	 * @param SeoService $pageSeo
	 *
	 * @return HeaderControl
	 */
	public function create(SeoService $pageSeo);
}