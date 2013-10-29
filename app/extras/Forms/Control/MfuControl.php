<?php

/**
 * This file is part of the Kdyby (http://www.kdyby.org)
 *
 * Copyright (c) 2008, 2012 Filip Procházka (filip@prochazka.su)
 *
 * For the full copyright and license information, please view the file license.txt that was distributed with this source code.
 */

namespace Extras\Forms\Control;

use Kdyby;
use Nette;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Http;



/**
 * @author Filip Procházka <filip@prochazka.su>
 *
 * @method onUpload(\Extras\Forms\Control\UploadControl $control, array $files)
 */
class MfuControl extends Nette\Forms\Controls\BaseControl
{

	/**
	 * @var array of function (UploadControl $control, Http\FileUpload[] $files)
	 */
	public $onUpload = array();

	/**
	 * @var Http\Request
	 */
	private $httpRequest;

	/**
	 * @var Http\Response
	 */
	private $httpResponse;



	/**
	 * @param string $label
	 */
	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->monitor('Nette\Application\UI\Presenter');
		$this->control->type = 'file';
	}



	/**
	 * @param \Nette\ComponentModel\Container $parent
	 * @throws \Nette\InvalidStateException
	 * @return void
	 */
	protected function attached($parent)
	{
		if ($parent instanceof Form) {
			if ($parent->getMethod() !== Form::POST) {
				throw new Nette\InvalidStateException('File upload requires method POST.');
			}
			$parent->getElementPrototype()->enctype = 'multipart/form-data';

		} elseif ($parent instanceof Nette\Application\UI\Presenter) {
			if (!$this->httpRequest) {
				$this->httpRequest = $parent->getContext()->httpRequest;
				$this->httpResponse = $parent->getContext()->httpResponse;
			}
		}

		parent::attached($parent);
	}



	/**
	 * @param \Nette\Http\Request $httpRequest
	 * @param \Nette\Http\Response $httpResponse
	 * @return UploadControl
	 */
	public function injectHttp(Http\Request $httpRequest, Http\Response $httpResponse)
	{
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		return $this;
	}



	/**
	 * @return UploadControl
	 */
	public function allowMultiple()
	{
		$this->control->multiple = TRUE;
		return $this;
	}



	/**
	 * Sets control's value.
	 *
	 * @param  array|Nette\Http\FileUpload
	 * @return Nette\Http\FileUpload  provides a fluent interface
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			if (Nette\Utils\Validators::isList($value)) {
				foreach ($value as $i => $file) {
					$this->value[$i] = $file instanceof Http\FileUpload ? $file : new Http\FileUpload($file);
				}

			} else {
				$this->value = array(new Http\FileUpload($value));
			}

		} elseif ($value instanceof Http\FileUpload) {
			$this->value = array($value);

		} else {
			$this->value = array(new Http\FileUpload(NULL));
		}

		return $this;
	}



	/**
	 */
	public function loadHttpData()
	{
		parent::loadHttpData();

		if ($this->value) {
			$this->onUpload($this, $this->value);
		}
	}

	public function getHttpData($type = Form::DATA_FILE, $htmlTail = NULL)
	{
		return parent::getHttpData($type, $htmlTail);
	}


	/**
	 * @return string
	 */
	public function getHtmlName()
	{
		return parent::getHtmlName() . '[]';
	}



	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl()
	{
		return parent::getControl()->data('url', $this->form->action);
	}



	/**
	 * Has been any file uploaded?
	 *
	 * @return bool
	 */
	public function isFilled()
	{
		foreach ((array)$this->value as $file) {
			if (!$file instanceof Http\FileUpload || !$file->isOK()) {
				return FALSE;
			}
		}

		return (bool)$this->value;
	}



//	/**
//	 * FileSize validator: is file size in limit?
//	 *
//	 * @param  UploadControl
//	 * @param  int  file size limit
//	 * @return bool
//	 */
//	public static function validateFileSize(UploadControl $control, $limit)
//	{
//		$file = $control->getValue();
//		return $file instanceof Http\FileUpload && $file->getSize() <= $limit;
//	}
//
//
//
//	/**
//	 * MimeType validator: has file specified mime type?
//	 *
//	 * @param  UploadControl
//	 * @param  array|string  mime type
//	 * @return bool
//	 */
//	public static function validateMimeType(UploadControl $control, $mimeType)
//	{
//		$file = $control->getValue();
//		if ($file instanceof Http\FileUpload) {
//			$type = strtolower($file->getContentType());
//			$mimeTypes = is_array($mimeType) ? $mimeType : explode(',', $mimeType);
//			if (in_array($type, $mimeTypes, TRUE)) {
//				return TRUE;
//			}
//			if (in_array(preg_replace('#/.*#', '/*', $type), $mimeTypes, TRUE)) {
//				return TRUE;
//			}
//		}
//		return FALSE;
//	}



	/**
	 * Image validator: is file image?
	 *
	 * @param UploadControl $control
	 * @return bool
	 */
	public static function validateImage(UploadControl $control)
	{
		foreach ((array)$control->value as $file) {
			if (!$file instanceof Http\FileUpload || !$file->isImage()) {
				return FALSE;
			}
		}

		return (bool)$control->value;
	}

}
