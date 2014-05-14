<?php
/**
 * This file is part of the tralandia.
 * User: macbook
 * Created at: 12/03/14 09:19
 */

namespace Tralandia\Lean;


use Nette;
use Nette\Utils\Json;

/**
 * Class BaseEntity
 * @package Tralandia\Lean
 *
 * @property int $id
 * @property \DateTime $created
 * @property \DateTime $updated
 */
class BaseEntity extends \LeanMapper\Entity
{


	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function jsonIn($string)
	{
		if($string === null) return null;

		return Json::decode($string, Json::FORCE_ARRAY);
	}


	/**
	 * @param $array
	 *
	 * @return string
	 */
	public function jsonOut($array)
	{
		return Json::encode($array);
	}

}
