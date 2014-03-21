<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property int $id
 */
class InterviewQuestion extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getQuestionId()
	{
		return $this->row->question_id;
	}

	/**
	 * @return int
	 */
	public function getQuestionFeId()
	{
		return $this->row->questionFe_id;
	}

}
