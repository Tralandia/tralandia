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

class RentalPresenter extends AdminPresenter {

	/**
	 * @autowire
	 * @var \Tralandia\SearchCache\InvalidateRentalListener
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

		$form->addText('url', 'Personal site url: http://');
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->personalSiteSetupFormOnSuccess;

		return $form;
	}

	public function personalSiteSetupFormOnSuccess(Form $form)
	{
		$values = $form->getValues();

		$rental = $this->findRental($this->getParameter('id'));
		$rental->personalSiteUrl = $values->url;

		$this->rentalDao->save($rental);

		$this->prolongService($rental, Service::GIVEN_FOR_PAID_INVOICE, Service::TYPE_PERSONAL_SITE);
	}



	public function prolongService(Rental $rental, $serviceFor, $serviceType = Service::TYPE_FEATURED)
	{
		$this->serviceManager->prolong($rental, $serviceFor, $serviceType);
		$invalidateOption = [
			\Tralandia\SearchCache\InvalidateRentalListener::CLEAR_SEARCH,
			\Tralandia\SearchCache\InvalidateRentalListener::CLEAR_HOMEPAGE,
		];
		$this->invalidateRentalListener->onSuccess($rental, $invalidateOption);
		$this->flashMessage('done', self::FLASH_SUCCESS);
		$this->redirect('list');
	}

}
