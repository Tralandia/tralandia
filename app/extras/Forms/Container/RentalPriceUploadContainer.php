<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;

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

		$this['upload'] = $upload = new MfuControl();
		$upload->allowMultiple()->onUpload[] = $this->processUpload;

		$sort = [];
		if($rental) {
			$priceList = $rental->getPricelists();
			foreach($priceList as $row) {
				$sort[] = $row->getId();
			}
		}

		$this->addHidden('sort')->setDefaultValue(implode(',', $sort));
	}

	public function getMainControl()
	{
		return $this['upload'];
	}

	/**
	 * @return array|null
	 */
	public function getPriceList()
	{
		$priceList = $this->getValues()->priceList;
		if(!count($priceList)) return [];
		return $priceList;
	}


	/**
	 * @param \Extras\Forms\Control\MfuControl $upload
	 * @param array|\Nette\Http\FileUpload[] $files
	 */
	public function processUpload(MfuControl $upload, array $files)
	{
		$payload = [];
		foreach($files as $file) {
			if($file->isOk() && $file->isImage()) {
				$image = $this->manager->upload($file);
				$payload[] = [
					'id' => $image->getId(),
					'name' => $image->getName(),
				];
			}
		}

		if ($this->getForm()->getPresenter()->isAjax() && $payload) {
			$this->getForm()->getPresenter()->sendJson($payload);
		}
	}

	public function getValues($asArray = FALSE)
	{
		$values = $asArray ? array() : new \Nette\ArrayHash;
		$sort = $this['sort']->getValue();
		$values['sort'] = array_filter(explode(',', $sort));

		$temp = [];
		if(count($values['sort'])) {
			$priceList = $this->pricelistRepository->findById($values['sort']);

			$temp = array_flip($values['sort']);
			foreach($priceList as $row) {
				$temp[$row->getId()] = $row;
			}
		}
		$values['priceList'] = $temp;

		return $values;
	}



}
