<?php

namespace FrontModule;

class DownloadPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Image\RentalPriceListPipe
	 */
	protected $pricelistPipe;

	public function actionPricelist($id)
	{
		$pricelist = $this->findPricelist($id);

		$this->redirectUrl($this->pricelistPipe->request($pricelist));
	}

}
