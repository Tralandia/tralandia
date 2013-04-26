<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;
use Nette\Forms\Container;

class RentalPriceUploadContainer extends BaseContainer
{

	/**
	 * @var \Entity\Rental\Rental
	 */
	protected $rental;

	/**
	 * @var \RentalPriceListManager
	 */
	protected $manager;

	protected $pricelistRepository;


	public function __construct(\Entity\Rental\Rental $rental = NULL, \RentalPriceListManager $manager, $pricelistRepository)
	{
		parent::__construct();
		$this->rental = $rental;
		$this->manager = $manager;
		$this->pricelistRepository = $pricelistRepository;


		$this->addDynamic('list', $this->containerBuilder,2);

	}

	public function containerBuilder(Container $container)
	{
		$container->addText('name', '#name');
		$container->addSelect('language', '#language', [2,3,4,5]);
		$container->addUpload('file', '#file');
	}

	public function getMainControl()
	{
		return NULL;
	}


}
