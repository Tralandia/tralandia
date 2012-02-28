<?php

namespace Dictionary;



/**
 * @Entity()
  * @Table(name="DictionaryQuality")
 */
class Quality extends \BaseEntity
{

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 * @Column(type="string")
	 */
	protected $value;



	/**
	 * @param string $name
	 * @return Quality
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @param string $value
	 * @return Quality
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

}
