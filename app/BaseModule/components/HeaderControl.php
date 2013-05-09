<?php
namespace BaseModule\Components;

use Doctrine\ORM\EntityManager;
use Entity\User\User;
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

	/**
	 * @var \Entity\User\User
	 */
	protected $user;

	/**
	 * @var \ShareLinks
	 */
	protected $shareLinks;

	public function __construct(SeoService $pageSeo, User $user = NULL, Environment $environment, EntityManager $em,
								ISeoServiceFactory $seoFactory, \ShareLinks $shareLinks) {
		parent::__construct();
		$this->pageSeo = $pageSeo;
		$this->user = $user;
		$this->environment = $environment;
		$this->em = $em;
		$this->seoFactory = $seoFactory;
		$this->shareLinks = $shareLinks;
	}

	public function render() {

		$template = $this->template;

		/** @var $languageRepository \Repository\LanguageRepository */
		$languageRepository = $this->em->getRepository('\Entity\Language');
		$translator = $this->environment->getTranslator();
		$collator = $this->environment->getLocale()->getCollator();
		$supportedLanguages = $languageRepository->getSupportedSortedByName($translator, $collator);

		$primaryLocation = $this->environment->getPrimaryLocation();

		$domain = $primaryLocation->getFirstDomain()->getDomain();

		if ($primaryLocation->slug == 'world') {
			$template->slogan = $template->translate('o100163');
		} else {
			$template->slogan = $template->translate('o21083').' '.$template->translate(
				$primaryLocation->getName(),
				NULL,
				array(\Extras\Translator::VARIATION_CASE => \Entity\Language::LOCATIVE)
			);
		}

		$template->localeCode = $this->environment->getLocale()->getCode();
		$template->localeGoogleCode = $this->environment->getLocale()->getGooglePlusLangCode();

		$homepageUrl = $this->presenter->link('//:Front:Home:');
		$template->homepageUrl = $homepageUrl;
		$template->homepageSeo = $this->seoFactory->create($template->homepageUrl, $this->presenter->getLastCreatedRequest());
		$template->domainHost = ucfirst(strstr($domain, '.', TRUE));
		$template->domainExtension = strstr($domain, '.');


		$template->supportedLanguages = array_chunk($supportedLanguages, round(count($supportedLanguages)/3));

		$template->environment = $this->environment;
		$template->pageSeo = $this->pageSeo;
		$template->loggedUser = $this->user;


		$shareLinks = $this->shareLinks;

		$shareLink = $homepageUrl;
		$shareText = $template->translate('o100036');
		// @todo toto je tu len docasne lebo sa neimportuju obrazky
		//$shareImage = $this->rentalImagePipe->request($rental->getMainImage());
		$shareImage = '';
		$template->twitterShareTag = $shareLinks->getTwitterShareTag($shareLink, $shareText);
		$template->googlePlusShareTag = $shareLinks->getGooglePlusShareTag($shareLink);
		$template->facebookShareTag = $shareLinks->getFacebookShareTag($shareLink, $shareText);
		$template->pinterestShareTag = $shareLinks->getPinterestShareTag($shareLink, $shareText, $shareImage);

		$template->facebookPageShageTag = $shareLinks->getFacebookPageShareTag();
		$template->googlePlusProfileShareTag = $shareLinks->getGooglePlusProfileShareTag();

		$template->render();
	}


}

interface IHeaderControlFactory {
	/**
	 * @param SeoService $pageSeo
	 * @param \Entity\User\User $user
	 *
	 * @return HeaderControl
	 */
	public function create(SeoService $pageSeo, User $user = NULL);
}
