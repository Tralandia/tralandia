<?php
/**
 * This file is part of the Tralandia.
 * User: david
 * Created at: 7/25/13 8:29 AM
 */

namespace AdminModule;


use Entity\Rental\Rental;
use Entity\Rental\Service;
use Entity\Seo\BackLink;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

class RentalPresenter extends AdminPresenter {

	/**
	 * @autowire
	 * @var \Tralandia\RentalSearch\InvalidateRentalListener
	 */
	protected $invalidateRentalListener;

	/**
	 * @autowire
	 * @var \Tralandia\Rental\Discarder
	 */
	protected $discarder;

	/**
	 * @autowire
	 * @var \Tralandia\Rental\ServiceManager
	 */
	protected $serviceManager;

	public function actionDiscard($id)
	{
		$rental = $this->findRental($id);
		$this->checkPermission($rental, 'discard');

		$this->discarder->discard($rental);
		$this->flashMessage("Rental $id deleted!", self::FLASH_SUCCESS);
		$this->redirect(':Admin:Language:list');
	}

	public function actionBacklink($id)
	{
		$this->template->rentalId = $id;
	}

	public function createComponentBacklinkForm()
	{
		$form = new Form;

		$form->addText('url', 'Backlink url: ')->addRule(Form::URL);
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->backlinkFormOnSuccess;

		return $form;
	}

	public function backlinkFormOnSuccess(Form $form)
	{
		$values = $form->getValues();

		$rental = $this->findRental($this->getParameter('id'));
		$backlink = $this->em->getRepository(SEO_BACKLINK_ENTITY)->createNew();
		$backlink->setUrl($values->url);
		$rental->addBackLink($backlink);

		$this->rentalDao->save($rental, $backlink);

		$this->prolongService($rental, Service::GIVEN_FOR_BACKLINK);
	}

	public function actionProlongService($id)
	{
		$rental = $this->findRental($id);
		$this->prolongService($rental, Service::GIVEN_FOR_SHARE);
	}

	public function actionActivatePersonalSite($id)
	{
		$this->template->rentalId = $id;
	}

	public function createComponentPersonalSiteSetupForm()
	{
		$form = new Form;

		$rental = $this->findRental($this->getParameter('id'));

		$form->addText('url', 'Personal site url:')
			->setDefaultValue($rental->getPersonalSiteUrl())
			->addRule(Form::URL, 'Zadaj validne URL');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->personalSiteSetupFormOnSuccess;

		return $form;
	}

	public function personalSiteSetupFormOnSuccess(Form $form)
	{
		$values = $form->getValues();
		$rentalId = $this->getParameter('id');

		$url = Strings::replace($values->url, '~^(https?://)?~', null);
		if(Strings::contains($url, '.tralandia.') || Strings::contains($url, '.uns.')) {
			$url = Strings::replace($url, '~^(www.)?~', null);
			if(!$match = Strings::match($url, '~([[a-z0-9-]{4,})\.(tralandia|uns)\.(.*)~')) {
				$form->addError('Subdomena musi mat viac ako 3 znaky a moze obsahovat len: "a-z", "0-9" alebo "-".');
			}
		} else if(!Strings::startsWith($url, 'www.')) {
			$form->addError('Domena musi zacinat na www.');
		}


		$psConfigurationRepository = $this->em->getRepository(PERSONAL_SITE_CONFIGURATION_ENTITY);

		$psConfiguration = $psConfigurationRepository->findOneBy(['url' => $url]);
		if($psConfiguration && $psConfiguration->rental->id != $rentalId) {
			$form->addError('Tato domena je uz priradena objektu. >> id: '.$psConfiguration->rental->id.', email: '.$psConfiguration->rental->getContactEmail());
		}

		if($form->hasErrors()) {
			return null;
		}

		$rental = $this->findRental($rentalId);
		$psConfiguration = $rental->personalSiteConfiguration;

		$isNewPs = null;
		/** @var $psConfiguration \Entity\PersonalSite\Configuration */
		if(!$psConfiguration) {
			$psConfiguration = $psConfigurationRepository->createNew();
			$psConfiguration->rental = $rental;
			$rental->personalSiteConfiguration = $psConfiguration;
			$isNewPs = true;
		}
		$psConfiguration->url = $url;

		$this->rentalDao->save($rental, $psConfiguration);

		if($isNewPs && $rental->getPrimaryLocation()->getIso() == 'sk') { // lebo je clenom UNS.sk
			$this->prolongService($rental, Service::GIVEN_FOR_MEMBERSHIP, Service::TYPE_PREMIUM_PS);
		}

		$this->flashMessage('done', self::FLASH_SUCCESS);
		$this->redirect('list');
	}



	public function prolongService(Rental $rental, $serviceFor, $serviceType = Service::TYPE_FEATURED)
	{
		$this->serviceManager->prolong($rental, $serviceFor, $serviceType);
		$invalidateOption = [
			\Tralandia\RentalSearch\InvalidateRentalListener::CLEAR_SEARCH,
			\Tralandia\RentalSearch\InvalidateRentalListener::CLEAR_HOMEPAGE,
		];

		$this->invalidateRentalListener->onSuccess($rental, $invalidateOption);

		$this->flashMessage('done', self::FLASH_SUCCESS);
		$this->redirect('list');
	}

}
