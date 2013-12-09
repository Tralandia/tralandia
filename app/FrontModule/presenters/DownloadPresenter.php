<?php

namespace FrontModule;

class DownloadPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \RentalPriceListManager
	 */
	protected $pricelistManager;

	/**
	 * @autowire
	 * @var \Image\RentalPriceListPipe
	 */
	protected $pricelistPipe;

	public function actionPricelist($id)
	{
		$pricelist = $this->findPricelist($id);

		$this->redirectUrl($this->pricelistPipe->request($pricelist));

		$fileName = $this->pricelistManager->getAbsolutePath($pricelist);

		if(!$fileName || !is_file($fileName)) {
			throw new \Nette\Application\BadRequestException();
		}


		$fileInfo = new \finfo(FILEINFO_MIME);

		$type = $fileInfo->file($fileName);

		header("Content-type: ".$type);
		header("Content-Disposition: attachment;filename=" . $pricelist->getName());
		header("Content-Transfer-Encoding: binary");
		header('Pragma: no-cache');
		header('Expires: 0');
		// Send the file contents.
		set_time_limit(0);
		readfile($fileName);

		$this->terminate();
	}

}
