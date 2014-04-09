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

		$form->addText('url', 'Personal site url:')
			->addRule(Form::URL, 'Zadaj validne URL');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->personalSiteSetupFormOnSuccess;

		return $form;
	}

	public function personalSiteSetupFormOnSuccess(Form $form)
	{
		lt('save','personalSiteTL');
		$values = $form->getValues();

		$url = Strings::replace($values->url, '~^(https?://)?~', null);
		if(Strings::contains($url, '.tralandia.') || Strings::contains($url, '.uns.')) {
			$url = Strings::replace($url, '~^(www.)?~', null);
			if(!$match = Strings::match($url, '~([[a-z0-9-]{4,})\.(tralandia|uns)\.(.*)~')) {
				$form->addError('Subdomena musi mat viac ako 3 znaky a moze obsahovat len: "a-z", "0-9" alebo "-".');
			}
		} else if(!Strings::startsWith($url, 'www.')) {
			$form->addError('Domena musi zacinat na www.');
		}


		if($rental = $this->findRental($url, false, 'personalSiteUrl')) {
			$form->addError('Tato domena je uz priradena objektu. >> id: '.$rental->getId().', email: '.$rental->getContactEmail());
		}

		if($form->hasErrors()) {
			return null;
		}

		$rental = $this->findRental($this->getParameter('id'));
		$rental->personalSiteUrl = $url;

		$this->rentalDao->save($rental);
		lt('save','personalSiteTL');

		$this->prolongService($rental, Service::GIVEN_FOR_PAID_INVOICE, Service::TYPE_PERSONAL_SITE);
	}



	public function prolongService(Rental $rental, $serviceFor, $serviceType = Service::TYPE_FEATURED)
	{
		lt('prolong','personalSiteTL');
		$this->serviceManager->prolong($rental, $serviceFor, $serviceType);
		lt('prolong','personalSiteTL');
		$invalidateOption = [
			\Tralandia\RentalSearch\InvalidateRentalListener::CLEAR_SEARCH,
			\Tralandia\RentalSearch\InvalidateRentalListener::CLEAR_HOMEPAGE,
		];
		lt('invalidateCache','personalSiteTL');
		$this->invalidateRentalListener->onSuccess($rental, $invalidateOption);
		lt('invalidateCache','personalSiteTL');

		$this->flashMessage('done', self::FLASH_SUCCESS);
		$this->redirect('list');
	}

}
