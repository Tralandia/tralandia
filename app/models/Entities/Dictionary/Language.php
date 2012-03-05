<?php

namespace Dictionary;

use Doctrine\ORM\Mapping as ORM;
use Dictionary;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="DictionaryLanguage")
 */
class Language extends \BaseEntityDetails
{

	/**
	 * @var Collection
	 * @OneToOne(targetEntity="Phrase")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $iso;

	/**
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $supported;

	// /**
	//  * @var Collection
	//  * @ORM\ManyToMany(targetEntity="Dictionary\Phrase")
	//  */
	// protected $phrase;


	/**
	 * @param Dictionary\Phrase $name
	 * @return Language
	 */
	public function setName(Phrase  $name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return Dictionary\Phrase
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $iso
	 * @return Language
	 */
	public function setIso($iso)
	{
		$this->iso = $iso;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getIso()
	{
		return $this->iso;
	}


	/**
	 * @param boolean $supported
	 * @return Language
	 */
	public function setSupported($supported)
	{
		$this->supported = $supported;
		return $this;
	}


	/**
	 * @return boolean
	 */
	public function getSupported()
	{
		return $this->supported;
	}

}
