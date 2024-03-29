<?php
/**
 * This file is part of the Harvester.
 * User: david
 * Created at: 6/14/13 8:48 AM
 */

namespace Tralandia\Rental;

use Nette;


/**
 * @property \Tralandia\Phrase\Phrase $answer m:hasOne(answer_id:)
 * @property \Tralandia\Rental\InterviewQuestion $question m:hasOne(question_id:rental_interviewquestion)
 */
class InterviewAnswer extends \Tralandia\Lean\BaseEntity
{

	/**
	 * @return int
	 */
	public function getAnswerId()
	{
		return $this->row->answer_id;
	}

}
