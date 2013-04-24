<?php

class Device {

	const MOBILE = 'mobile';
	const DESKTOP = 'desktop';

	/**
	 * @var Mobile_Detect
	 */
	protected $deviceDetect;

	/**
	 * @var Nette\Http\SessionSection
	 */
	protected $section;


	/**
	 * @param Mobile_Detect $deviceDetect
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(Mobile_Detect $deviceDetect, \Nette\Http\Session $session)
	{
		$this->deviceDetect = $deviceDetect;
		$this->section = $session->getSection('device');
	}


	public function setDevice($device)
	{
		$this->section->device = $device;
	}


	public function getDevice()
	{
		return $this->section->device;
	}


	public function isSetManually()
	{
		return $this->section->device !== NULL;
	}


	/**
	 * @return bool
	 */
	public function isMobile()
	{
		if($this->section->device == self::MOBILE) {
			return TRUE;
		} else if($this->section->device == self::DESKTOP) {
			return FALSE;
		} else {
			return $this->deviceDetect->isMobile();
		}
	}

}
