<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CountryRepository")
 * @ORM\Primary(key="id", value="iso")
 */
class Country extends BaseEntity {

	/**
	 * @ORM\Column(type="string")
	 * @UIControl(type="text")
	 */
	protected $iso;

	/**
	 * @ORM\ManyToOne(targetEntity="\Dictionary\Language")
	 * @UIControl(type="select", options="%service%, getList")
	 */
	protected $language;
}
