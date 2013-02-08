<?php

namespace Extras\Forms\Container;

use Extras\Forms\Control\MfuControl;

class RentalPhotosContainer extends BaseContainer
{

	public function __construct()
	{
		parent::__construct();

		$this['upload'] = $upload = new MfuControl();
		$upload->allowMultiple()->onUpload[] = $this->processUpload;
	}

	public function getMainControl()
	{
		return $this['upload'];
	}

	public function processUpload(MfuControl $upload, array $files)
	{
		d($files);
	}
}
